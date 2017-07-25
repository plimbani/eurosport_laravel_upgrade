//
//  AgeTeamListVC.h
//  ESR
//
//  Created by Aecor Digital on 29/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AgeTeamListVC : UIViewController<UITableViewDataSource,UITableViewDelegate>
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *ageTeamArray;
- (IBAction)backBtnClick:(id)sender;
@property (strong, nonatomic) NSMutableDictionary *ageDir;
@end
