//
//  TournamentListVC.h
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Reachability.h"

@interface TournamentListVC : UIViewController <UITableViewDelegate, UITableViewDataSource, UISearchBarDelegate, UISearchControllerDelegate> {
    int defaultFlag;
    int defaultFavouriteFlag;
}

@property (strong, nonatomic) NSMutableArray *selectedArray;
@property (strong, nonatomic) NSMutableArray *searchListArray;
@property (strong, nonatomic) NSMutableArray *tournamentlistArray;
@property (nonatomic, strong) NSMutableDictionary *defaultTournamentDir;

@property (weak, nonatomic) IBOutlet UIView *alertView;
@property (weak, nonatomic) IBOutlet UILabel *alertTitle;
@property (weak, nonatomic) IBOutlet UILabel *alertSubTitle;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UISearchBar *searchBar;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

- (IBAction)alertViewOkBtnClick:(id)sender;

@end
