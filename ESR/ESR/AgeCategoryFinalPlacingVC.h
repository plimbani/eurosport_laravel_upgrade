//
//  AgeCategoryFinalPlacingVC.h
//  ESR
//
//  Created by Aecor Digital on 09/02/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AgeCell.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"

@interface AgeCategoryFinalPlacingVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UISearchBarDelegate>
@property (weak, nonatomic) IBOutlet UILabel *ageCategoriesLbl;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *agelistArray;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UIView *commentView;
@property (weak, nonatomic) IBOutlet UILabel *commentLbl;
- (IBAction)closeBtnClick:(id)sender;
- (IBAction)backBtnClick:(id)sender;

@end
