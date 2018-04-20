//
//  TeamDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TeamDetailVC : UIViewController<UITableViewDelegate,UITableViewDataSource,UIGestureRecognizerDelegate>
@property (strong, nonatomic) NSMutableDictionary *teamDetails;
@property (strong, nonatomic) NSMutableArray *fixturesArray;
@property (strong, nonatomic) NSMutableArray *standingArray;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
- (IBAction)backBtnClick:(id)sender;
@end
