//
//  LeagueDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 04/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "LeagueDetailVC.h"
#import "LeagueDetailCel.h"
#import "LeagueDetailHeaderCell.h"
#import "AppDelegate.h"
#import "UIColor+fromHex.h"
#import "Reachability.h"
#import "Utils.h"

@interface LeagueDetailVC ()

@end

@implementation LeagueDetailVC
@synthesize leagueArray;
@synthesize teamDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    NSLog(@"%@",teamDetails);
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    if ([app.selectedTab isEqualToString:@"1"]) {
        self.titleLbl.text =[[teamDetails valueForKey:@"group_name"] uppercaseString];
    }else{
        self.titleLbl.text =[[teamDetails valueForKey:@"name"] uppercaseString];
    }
    self.tabBarController.tabBar.hidden = TRUE;
//    for (int i= 0; i<leagueArray.count; i++) {
//        if([[teamDetails valueForKey:@"name"]isEqualToString:[[leagueArray objectAtIndex:i] valueForKey:@"name"]]){
//            self.titleLbl.text =[[leagueArray objectAtIndex:i] valueForKey:@"name"];
//        }
//    }
    
    //self.isPresented = YES;
    // Do any additional setup after loading the view.
//    [[UIDevice currentDevice] setValue:
//     [NSNumber numberWithInteger: UIInterfaceOrientationMaskLandscape]
//                                forKey:@"orientation"];
    
//    [self.navigationController shouldAutorotate];
//    [self.navigationController supportedInterfaceOrientations ];
////    [self.navigationController shouldAutorotateToInterfaceOrientation:UIInterfaceOrientationLandscapeLeft];
//    [self.navigationController preferredInterfaceOrientationForPresentation];
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _titleLbl.userInteractionEnabled = YES;
    [_titleLbl addGestureRecognizer:tapAction];
}
- (void)lblClick:(UITapGestureRecognizer *)tapGesture {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
-(void)viewWillAppear:(BOOL)animated{
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.orientationFlag = @"1";
     [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}
- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    [super preferredInterfaceOrientationForPresentation ];
    return UIInterfaceOrientationLandscapeLeft;
}
- (BOOL)shouldAutorotate
{
    [super shouldAutorotate];
    return NO;
}

- (UIInterfaceOrientationMask)supportedInterfaceOrientations
{
    [super supportedInterfaceOrientations];
    NSLog(@"%lu",(unsigned long)UIInterfaceOrientationMaskLandscape);
    return UIInterfaceOrientationMaskLandscape;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    
    return UIInterfaceOrientationLandscapeLeft;
}



/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return 60 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return leagueArray.count+1;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row == 0) {
        LeagueDetailHeaderCell *cell = (LeagueDetailHeaderCell*)[tableView dequeueReusableCellWithIdentifier:@"LeagueDetailHeaderCell"];
//        cell.gName.text= [[leagueArray objectAtIndex:indexPath.row] valueForKey:@"assigned_group"];
//        for (int i= 0; i<leagueArray.count; i++) {
//            if([[teamDetails valueForKey:@"name"]isEqualToString:[[leagueArray objectAtIndex:i] valueForKey:@"name"]]){
//                cell.gName.text =[[leagueArray objectAtIndex:i] valueForKey:@"name"];
//            }
//        }
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        if ([app.selectedTab isEqualToString:@"1"]) {
            cell.gName.text =[teamDetails valueForKey:@"group_name"];
        }else{
            cell.gName.text =[teamDetails valueForKey:@"name"];
        }
        cell.backgroundColor = [UIColor colorwithHexString:@"DEDFE2" alpha:1.0];
        return cell;
    }else{
        LeagueDetailCel *cell = (LeagueDetailCel*)[tableView dequeueReusableCellWithIdentifier:@"LeagueDetailCel"];
//        cell.lbl.text = [[_agelistArray objectAtIndex:indexPath.row] valueForKey:@"category_age"];
        //cell.lblRowData.text=[[[[screen objectForKey:@"options"] objectForKey:@"optionList"] objectAtIndex:indexPath.row] objectForKey:@"text"];
        if (![[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"teamFlag"] isKindOfClass:[NSNull class]]) {
            NSURL *url = [NSURL URLWithString:[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"teamFlag"]];
            NSURLRequest* request = [NSURLRequest requestWithURL:url];
            [NSURLConnection sendAsynchronousRequest:request
                                               queue:[NSOperationQueue mainQueue]
                                   completionHandler:^(NSURLResponse * response,
                                                       NSData * data,
                                                       NSError * error) {
                                       if (!error){
                                           UIImage *image = [UIImage imageWithData:data];
                                           cell.image.image = image;                               }
                                   }];
        }else{
            cell.imagewidthConstrain.constant = 30;
            cell.imageHeightConstrain.constant = 30;
        }
        
        cell.lbl.text = [[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"name"];
        cell.gLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"played"]];
        cell.pLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"points"]];
        int differance = [[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_for"] intValue] -[[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"] intValue];
        NSLog(@"%d",differance);
        if (differance > 0) {
            cell.plusMinusLbl.text = [NSString stringWithFormat:@"%@%d",@"+",differance];
        }else{
            cell.plusMinusLbl.text = [NSString stringWithFormat:@"%d",differance];
        }
        if (![[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"team_id"] isKindOfClass:[NSNull class]]) {
            if([[[teamDetails valueForKey:@"id"] stringValue]isEqualToString:[[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"team_id"] stringValue]]){
                cell.backgroundColor = [UIColor colorwithHexString:@"c5dba7" alpha:1.0];
            }
        }
        cell.wLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"won"]];
        cell.dLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"draws"]];
        //cell.faLbl.text = [NSString stringWithFormat:@"%@-%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_for"],[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"]];
        cell.faLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_for"]];
        cell.aLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"]];
        cell.lLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"lost"]];
//        NSURL *url = [NSURL URLWithString:[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"teamFlag"]];
//        NSURLRequest* request = [NSURLRequest requestWithURL:url];
//        [NSURLConnection sendAsynchronousRequest:request
//                                           queue:[NSOperationQueue mainQueue]
//                               completionHandler:^(NSURLResponse * response,
//                                                   NSData * data,
//                                                   NSError * error) {
//                                   if (!error){
//                                       UIImage *image = [UIImage imageWithData:data];
//                                       //cell.image.image = image;                               }
//                               }];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
        
    }
    
    return 0;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
}
- (IBAction)backBtnClick:(id)sender {
    self.tabBarController.tabBar.hidden = FALSE;
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.selectedTab = @"0";
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
