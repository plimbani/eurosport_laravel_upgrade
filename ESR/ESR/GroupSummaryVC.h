//
//  GroupSummaryVC.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GroupSummaryVC : UIViewController<UIPickerViewDataSource, UIPickerViewDelegate,UIGestureRecognizerDelegate>{
    NSString *tabSelectionStr;
}
@property (strong, nonatomic) NSMutableArray *pickerViewArray;
@property (weak, nonatomic) IBOutlet UIView *pickerView;
@property (weak, nonatomic) IBOutlet UIPickerView *picker;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UILabel *titlelbl;
@property (strong, nonatomic) NSMutableDictionary *groupDetails;
@property (strong, nonatomic) NSString *selectedGroupStr;
@property (strong, nonatomic) NSMutableArray *fixturesArray;
@property (strong, nonatomic) NSMutableArray *standingArray;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
- (IBAction)backBtnClick:(id)sender;
@end
