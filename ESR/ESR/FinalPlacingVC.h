//
//  FinalPlacingVC.h
//  ESR
//
//  Created by Aecor Digital on 09/02/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FinalPlacingCell.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"

@interface FinalPlacingVC : UIViewController<UITableViewDelegate,UITableViewDataSource>
@property (weak, nonatomic) IBOutlet UILabel *ageCategoriesLbl;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
- (IBAction)backBtnClick:(id)sender;
@property (strong, nonatomic) NSMutableArray *placinglistArray;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (strong, nonatomic) NSMutableDictionary *finalPlacingDir;


@end
