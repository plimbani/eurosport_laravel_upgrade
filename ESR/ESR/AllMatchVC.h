//
//  AllMatchVC.h
//  ESR
//
//  Created by Aecor Digital on 05/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AllMatchVC : UIViewController<UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate>{
    
}
@property (weak, nonatomic) IBOutlet UIView *offlineView;
@property (weak, nonatomic) IBOutlet UILabel *teamNameLbl;
@property (strong, nonatomic) NSMutableDictionary *teamDetails;
@property (strong, nonatomic) NSMutableArray *fixturesArray;;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
- (IBAction)backBtnClick:(id)sender;
@end
