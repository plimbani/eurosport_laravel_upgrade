//
//  ClubTeamListVC.h
//  ESR
//
//  Created by Aecor Digital on 29/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ClubTeamListVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate>
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *clubTeamArray;
- (IBAction)backBtnClick:(id)sender;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (strong, nonatomic) NSMutableDictionary *clubDir;
@end
