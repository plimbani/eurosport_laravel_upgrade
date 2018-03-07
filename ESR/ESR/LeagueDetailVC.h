//
//  LeagueDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LeagueDetailVC : UIViewController<UITableViewDelegate,UITableViewDataSource,UIGestureRecognizerDelegate>
@property (strong, nonatomic) NSMutableArray *leagueArray;
@property (strong, nonatomic) NSMutableDictionary *teamDetails;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;
@property (nonatomic) BOOL isPresented;
@property (weak, nonatomic) IBOutlet UIView *offlineView;
//- (IBAction)dismiss:(id)sender;
@end
