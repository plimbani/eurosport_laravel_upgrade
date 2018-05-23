//
//  TableCellOwner.m
//

#import "TableCellOwner.h"

@implementation TableCellOwner

@synthesize cell;
@synthesize view;

- (BOOL)loadMyNibFile:(NSString *)nibName {
    // The myNib file must be in the bundle that defines self's class.
    if ([[NSBundle mainBundle] loadNibNamed:nibName owner:self options:nil] == nil)
    {
        return NO;
    }
	
    return YES;
}


@end
