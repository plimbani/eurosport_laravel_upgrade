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

@interface LeagueDetailVC ()

@end

@implementation LeagueDetailVC
@synthesize leagueArray;
@synthesize teamDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    NSLog(@"%@",leagueArray);
    //self.isPresented = YES;
    // Do any additional setup after loading the view.
//    [[UIDevice currentDevice] setValue:
//     [NSNumber numberWithInteger: UIInterfaceOrientationMaskLandscape]
//                                forKey:@"orientation"];
    
//    [self.navigationController shouldAutorotate];
//    [self.navigationController supportedInterfaceOrientations ];
////    [self.navigationController shouldAutorotateToInterfaceOrientation:UIInterfaceOrientationLandscapeLeft];
//    [self.navigationController preferredInterfaceOrientationForPresentation];
   
}
-(void)viewWillAppear:(BOOL)animated{
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.orientationFlag = @"1";
     [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationLandscapeLeft) forKey:@"orientation"];
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
        cell.gName.text= [[leagueArray objectAtIndex:indexPath.row] valueForKey:@"assigned_group"];
        return cell;
    }else{
        LeagueDetailCel *cell = (LeagueDetailCel*)[tableView dequeueReusableCellWithIdentifier:@"LeagueDetailCel"];
//        cell.lbl.text = [[_agelistArray objectAtIndex:indexPath.row] valueForKey:@"category_age"];
        //cell.lblRowData.text=[[[[screen objectForKey:@"options"] objectForKey:@"optionList"] objectAtIndex:indexPath.row] objectForKey:@"text"];
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
        if([[teamDetails valueForKey:@"name"]isEqualToString:[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"name"]]){
            cell.backgroundColor = [UIColor colorwithHexString:@"c5dba7" alpha:1.0];
        }
        cell.wLbl.text = [NSString stringWithFormat:@"%@",[[leagueArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"]];
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
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
