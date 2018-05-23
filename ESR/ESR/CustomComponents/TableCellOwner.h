//
//  TableCellOwner.h
//

#import <Foundation/Foundation.h>

@interface TableCellOwner : NSObject {
	UITableViewCell *__unsafe_unretained cell;
}

@property (nonatomic, unsafe_unretained) IBOutlet UITableViewCell *cell;
@property (unsafe_unretained, nonatomic) IBOutlet UIView *view;

- (BOOL)loadMyNibFile:(NSString *)nibName;

@end
