//
//  AgeGroupVC.h
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AgeGroupVC : UIViewController<UITableViewDataSource,UITableViewDelegate>
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *ageGroupArray;
@property (strong, nonatomic) NSMutableDictionary *ageDir;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
- (IBAction)backBtnClick:(id)sender;
@end
