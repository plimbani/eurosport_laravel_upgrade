//
//  GroupTeamListVC.h
//  ESR
//
//  Created by Aecor Digital on 29/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GroupTeamListVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate>
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *groupTeamArray;
@property (strong, nonatomic) NSMutableDictionary *groupDir;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
- (IBAction)backBtnClick:(id)sender;
@end
