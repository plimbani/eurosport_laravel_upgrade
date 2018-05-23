//
//  FileManager.m
//  JoomlaDay
//
//

#import "UserFileManager.h"

#define SYSTEM_VERSION_EQUAL_TO(v)                  ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedSame)
#define SYSTEM_VERSION_GREATER_THAN(v)              ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedDescending)
#define SYSTEM_VERSION_GREATER_THAN_OR_EQUAL_TO(v)  ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] != NSOrderedAscending)
#define SYSTEM_VERSION_LESS_THAN(v)                 ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedAscending)
#define SYSTEM_VERSION_LESS_THAN_OR_EQUAL_TO(v)     ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] != NSOrderedDescending)

#import <sys/xattr.h>
#define kImageFolder @"userimages"
#define kFileFolder  @"userfiles"
#define kPathNameMapingFile @"pathnamemaping.plist"
#define kMinVersion @"5.0.1"
#define kMaxVersion @"5.1"

NSMutableDictionary *fileNameDictionary;

@implementation UserFileManager

- (id)init {
    self = [super init];
    if(self) {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        NSString *finalPath = [documentsDirectory stringByAppendingPathComponent:kPathNameMapingFile]; 
        if([[NSFileManager defaultManager] fileExistsAtPath:finalPath]) {
            fileNameDictionary = [[NSMutableDictionary alloc] initWithContentsOfFile:finalPath];
        } else {
            finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
            NSFileManager *systemFileManager = [NSFileManager defaultManager];
            if(![systemFileManager fileExistsAtPath:finalPath]) {
                [[NSFileManager defaultManager] createDirectoryAtPath:finalPath withIntermediateDirectories:YES attributes:nil error:nil];
            }
            
            finalPath = [documentsDirectory stringByAppendingPathComponent:kImageFolder];
            if(![systemFileManager fileExistsAtPath:finalPath]) {
                [[NSFileManager defaultManager] createDirectoryAtPath:finalPath withIntermediateDirectories:YES attributes:nil error:nil];
            }
            
            fileNameDictionary = [[NSMutableDictionary alloc] init];
        }
    }
    return self;
}

- (BOOL)addSkipBackupAttributeToItemAtURL:(NSURL *)URL {
    if(SYSTEM_VERSION_EQUAL_TO(kMinVersion)) {
        assert([[NSFileManager defaultManager] fileExistsAtPath: [URL path]]);
        
        const char* filePath = [[URL path] fileSystemRepresentation];
        const char* attrName = "com.apple.MobileBackup";
        u_int8_t attrValue = 1;
        int result = setxattr(filePath, attrName, &attrValue, sizeof(attrValue), 0, 0);
        return result == 0;
        
    } else if(SYSTEM_VERSION_GREATER_THAN(kMinVersion)) {
        assert([[NSFileManager defaultManager] fileExistsAtPath: [URL path]]);
        
        NSError *error = nil;
        BOOL success = [URL setResourceValue: [NSNumber numberWithBool: YES]
                                      forKey: NSURLIsExcludedFromBackupKey error: &error];
        if(!success){
            NSLog(@"Error excluding %@ from backup %@", [URL lastPathComponent], error);
        }
        return success;
        
    }
    return TRUE;
}


- (void)saveFilePathMapings {
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
    NSString *documentsDirectory = [paths objectAtIndex:0];
    NSString* finalPath = [documentsDirectory stringByAppendingPathComponent:kPathNameMapingFile]; 
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    if([[NSFileManager defaultManager] fileExistsAtPath:finalPath]) {
        [systemFileManager removeItemAtPath:finalPath error:nil];
    }
    if([fileNameDictionary writeToFile:finalPath atomically:YES]) {
        [self addSkipBackupAttributeToItemAtURL:[NSURL fileURLWithPath:finalPath]];
    }
}

- (NSData *)getContentForPath:(NSString *)fileName {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:fileName];
    if(finalPath && [finalPath length] > 0) {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        NSString *path = [documentsDirectory stringByAppendingPathComponent:[NSString stringWithFormat:@"/%@", kFileFolder]];
        path = [path stringByAppendingPathComponent:finalPath];
        
        NSFileManager *systemFileManager = [NSFileManager defaultManager];
        if([systemFileManager fileExistsAtPath:path]) {
            return [[NSData alloc] initWithContentsOfFile:path];
        }
    }
    return nil;
}

- (UIImage *)getImageForPath:(NSString *)fileName {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:fileName];
    if(finalPath && [finalPath length] > 0) {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        NSString *path = [documentsDirectory stringByAppendingPathComponent:[NSString stringWithFormat:@"/%@", kFileFolder]];
        path = [path stringByAppendingPathComponent:finalPath];
        
        NSFileManager *systemFileManager = [NSFileManager defaultManager];
        if([systemFileManager fileExistsAtPath:path]) {
            NSData *fileData = [NSData dataWithContentsOfFile:path];
            return [UIImage imageWithData:fileData];
        }
    }
    return nil;
}

- (BOOL)setContentForPath:(NSString *)filePath FileContent:(NSData *)fileData {
    NSString *finalPath = nil;
    NSString *storePath = [fileNameDictionary objectForKey:filePath];
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    NSString *contentPath = [NSString stringWithFormat:@"%lf", [[NSDate date] timeIntervalSince1970]];
    
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
    NSString *documentsDirectory = [paths objectAtIndex:0];
    finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
    
    if(storePath || [storePath length] > 0) {
        [systemFileManager removeItemAtPath:finalPath error:nil];
    }
    contentPath = [contentPath stringByReplacingOccurrencesOfString:@"." withString:@""];
    contentPath = [contentPath stringByAppendingString:@".txt"];
    finalPath = [finalPath stringByAppendingPathComponent:contentPath];

    if(fileData != nil) {
        BOOL isFileSaved = [systemFileManager createFileAtPath:finalPath contents:fileData attributes:nil];
        if(isFileSaved) {
            [fileNameDictionary setValue:contentPath forKey:filePath];
            [self saveFilePathMapings];
            [self addSkipBackupAttributeToItemAtURL:[NSURL fileURLWithPath:finalPath]];
        }
        return isFileSaved;
    }
    return TRUE;
}

- (BOOL)setContentForPathWithBackup:(NSString *)filePath FileContent:(NSData *)fileData {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:filePath];
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    NSString *contentPath = [NSString stringWithFormat:@"%lf", [[NSDate date] timeIntervalSince1970]];
    if(!finalPath || [finalPath length] == 0) {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
        contentPath = [contentPath stringByReplacingOccurrencesOfString:@"." withString:@""];
        contentPath = [contentPath stringByAppendingString:@".txt"];
        finalPath = [finalPath stringByAppendingPathComponent:contentPath];
    } else {
        [systemFileManager removeItemAtPath:finalPath error:nil]; 
    }

    if(fileData != nil) {
        BOOL isFileSaved = [systemFileManager createFileAtPath:finalPath contents:fileData attributes:nil];
        if(isFileSaved) {
            [fileNameDictionary setValue:contentPath forKey:filePath];
            [self saveFilePathMapings];
            //[self addSkipBackupAttributeToItemAtURL:[NSURL fileURLWithPath:finalPath]];
        }
        return isFileSaved;
    }
    return TRUE;
}

- (BOOL)setImageForPathWithBackup:(NSString *)filePath FileContent:(NSData *)fileData {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:filePath];
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    NSString *contentPath = [NSString stringWithFormat:@"%lf", [[NSDate date] timeIntervalSince1970]];
    if(!finalPath || [finalPath length] == 0) {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
        contentPath = [contentPath stringByReplacingOccurrencesOfString:@"." withString:@""];
        contentPath = [contentPath stringByAppendingFormat:@".%@",[filePath pathExtension]];
        finalPath = [finalPath stringByAppendingPathComponent:contentPath];
    } else {
        [systemFileManager removeItemAtPath:finalPath error:nil]; 
    }

    if(fileData != nil && [fileData length] > 0) {
        BOOL isFileSaved = [systemFileManager createFileAtPath:finalPath contents:fileData attributes:nil];
        if(isFileSaved) {
            [fileNameDictionary setValue:contentPath forKey:filePath];
            [self saveFilePathMapings];
        }
        return isFileSaved;
    } else {
        [fileNameDictionary removeObjectForKey:filePath];
        return YES;
    }

}

- (BOOL)setImageForPath:(NSString *)filePath FileContent:(NSData *)fileData {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:filePath];
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    NSString *contentPath = [NSString stringWithFormat:@"%lf", [[NSDate date] timeIntervalSince1970]];
    
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
    NSString *documentsDirectory = [paths objectAtIndex:0];
    
    if(!finalPath || [finalPath length] == 0) {
        finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
        contentPath = [contentPath stringByReplacingOccurrencesOfString:@"." withString:@""];
        contentPath = [contentPath stringByAppendingFormat:@".%@",[filePath pathExtension]];
        finalPath = [documentsDirectory stringByAppendingPathComponent:contentPath];
    } else {
        contentPath = finalPath;
        finalPath = [documentsDirectory stringByAppendingPathComponent:kFileFolder];
        finalPath = [documentsDirectory stringByAppendingPathComponent:contentPath];
        [systemFileManager removeItemAtPath:finalPath error:nil];
    }
    
    if(fileData != nil && [fileData length] > 0) {
        BOOL isFileSaved = [systemFileManager createFileAtPath:finalPath contents:fileData attributes:nil];
        if(isFileSaved) {
            [fileNameDictionary setValue:contentPath forKey:filePath];
            [self saveFilePathMapings];
            //[self addSkipBackupAttributeToItemAtURL:[NSURL fileURLWithPath:finalPath]];
        }
        return isFileSaved;
    } else {
        [fileNameDictionary removeObjectForKey:filePath];
        return YES;
    }
    
}


- (BOOL)deleteFileForPath:(NSString *)filePath {
    NSString *finalPath = nil;
    finalPath = [fileNameDictionary objectForKey:filePath];
    NSFileManager *systemFileManager = [NSFileManager defaultManager];
    if(finalPath && [finalPath length] > 0) {
        [systemFileManager removeItemAtPath:finalPath error:nil]; 
        [fileNameDictionary removeObjectForKey:filePath];
        [self saveFilePathMapings];
        return true;
    }
    return false;
}

- (NSString *)getFilePathForURL:(NSString *)filePath {
    return [fileNameDictionary objectForKey:filePath];
}

@end
