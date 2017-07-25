//
//  AgeCategoriesVC.h
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AgeCell.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"

@interface AgeCategoriesVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UISearchBarDelegate>
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *agelistArray;

@end
