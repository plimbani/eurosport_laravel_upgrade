//
//  GroupSummaryVC.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GroupSummaryVC : UIViewController
@property (strong, nonatomic) NSMutableDictionary *groupDetails;
@property (strong, nonatomic) NSMutableArray *fixturesArray;
@property (strong, nonatomic) NSMutableArray *standingArray;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
- (IBAction)backBtnClick:(id)sender;
@end
