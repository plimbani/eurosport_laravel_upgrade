//
//  SignUpTournamentList.h
//  ESR
//
//  Created by Aecor Digital on 20/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SignUpTournamentList : UIViewController<UITableViewDataSource,UITableViewDelegate>
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (strong, nonatomic) NSMutableArray *tournamentlistArray;
- (IBAction)backBtnClick:(id)sender;

@end
