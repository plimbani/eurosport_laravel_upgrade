//
//  LeagueDetailVC.h
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LeagueDetailVC : UIViewController<UITableViewDelegate,UITableViewDataSource>
@property (strong, nonatomic) NSMutableArray *leagueArray;
@property (strong, nonatomic) NSMutableDictionary *teamDetails;
@property (nonatomic) BOOL isPresented;
//- (IBAction)dismiss:(id)sender;
@end
