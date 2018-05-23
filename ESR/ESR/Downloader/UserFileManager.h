//
//  FileManager.h
//

#import <Foundation/Foundation.h>



@interface UserFileManager : NSObject {
    
}

- (NSData *)getContentForPath:(NSString *)fileName;
- (UIImage *)getImageForPath:(NSString *)fileName;
- (BOOL)setContentForPath:(NSString *)filePath FileContent:(NSData *)fileData;
- (BOOL)setImageForPath:(NSString *)filePath FileContent:(NSData *)fileData;
- (BOOL)setContentForPathWithBackup:(NSString *)filePath FileContent:(NSData *)fileData;
- (BOOL)setImageForPathWithBackup:(NSString *)filePath FileContent:(NSData *)fileData;
- (BOOL)deleteFileForPath:(NSString *)filePath;
- (void)saveFilePathMapings;
- (NSString *)getFilePathForURL:(NSString *)filePath;

@end
