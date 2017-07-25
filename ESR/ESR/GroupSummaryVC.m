//
//  GroupSummaryVC.m
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "GroupSummaryVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "FixturesCell.h"
#import "StandingCell.h"
#import "TeamDetailCell.h"
#import "StandingHeaderCell.h"
#import "ViewDetailCell.h"
#import "MatchDetailVC.h"
#import "AllMatchVC.h"
#import "LeagueDetailVC.h"

@interface GroupSummaryVC ()

@end

@implementation GroupSummaryVC
@synthesize groupDetails;
@synthesize standingArray,fixturesArray;
-(void)getStanding{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competationId":[groupDetails valueForKey:@"id"]};
        NSDictionary *params = @{@"tournamentData":dataDir };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetStanding];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  standingArray =[responseDictionary[@"data"] mutableCopy];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.tableView reloadData];
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)getMatchFixtures{
    
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSLog(@"%@",app.defaultTournamentDir);
        
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competationId":[groupDetails valueForKey:@"id"],@"is_scheduled":@"1" };
        NSDictionary *params = @{@"tournamentData":dataDir };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetMatchFixturesTeamID];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        NSData *requestData = [NSJSONSerialization dataWithJSONObject:params options:0 error:nil]; //TODO handle error
        [request setHTTPMethod:@"POST"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        [request setValue:[NSString stringWithFormat:@"%lu", (unsigned long)[requestData length]] forHTTPHeaderField:@"Content-Length"];
        [request setHTTPBody: requestData];
        NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request
                                                    completionHandler:^(NSData *data, NSURLResponse *response, NSError *error)
                                          {
                                              if (error) {
                                                  NSLog(@"data%@",data);
                                                  NSLog(@"response%@",error);
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  fixturesArray =[responseDictionary[@"data"] mutableCopy];
                                                  [self getStanding];
                                                  //                                                  NSLog(@"%@",_clubTeamArray);
                                                  //                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                  //                                                      [self.tableView reloadData];
                                                  //                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    //NSLog(@"%@",groupDetails);
    [self getMatchFixtures];
}
-(void)viewWillAppear:(BOOL)animated{
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.orientationFlag = @"0";
    [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
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
    
    if(indexPath.row == 0){
        return 60;
    }else if (indexPath.row <=standingArray.count) {
        return 60 ;
    }else if(indexPath.row == standingArray.count +1){
        return 60;
    }else if(indexPath.row == standingArray.count +2){
        return 60;
    }else if (indexPath.row <standingArray.count+3+fixturesArray.count){
        return 135 ;
    }else{
        return 60;
    }
    return 0;
    
    
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSLog(@"fixturesArray%@\nstandingArray%@",fixturesArray,standingArray);
    return fixturesArray.count+standingArray.count+3;
}

- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
    
    //    UILabel *typeLbl = (UILabel *)[self.view viewWithTag:100];
    //    UIButton *buttonsample = (UIButton *)[self.view viewWithTag:101];
    //   // buttonsample.tag = 102+indexPath.row;
    //    [buttonsample addTarget:self action:@selector(handleButtonClick:) forControlEvents:UIControlEventTouchUpInside];
    //    typeLbl.text = @"testddsj";
    
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if(indexPath.row == 0){
        StandingHeaderCell *cell = (StandingHeaderCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingHeaderCell"];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    else if (indexPath.row <=standingArray.count) {
        StandingCell *cell = (StandingCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingCell"];
        cell.name.text = [[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"name"];
        NSLog(@"%@",[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"played"]);
        cell.played.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"played"]];
        cell.points.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"points"]];
        int differance = [[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_for"] intValue] -[[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"] intValue];
        NSLog(@"%d",differance);
        if (differance > 0) {
            cell.plusMinus.text = [NSString stringWithFormat:@"%@%d",@"+",differance];
        }else{
            cell.plusMinus.text = [NSString stringWithFormat:@"%d",differance];
        }
        NSURL *url = [NSURL URLWithString:[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"teamFlag"]];
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
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else if (indexPath.row ==standingArray.count+1) {
        ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
        cell.lbl.text = @"View full league table";
        cell.rightArrowBtn.hidden = FALSE;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else if (indexPath.row ==standingArray.count+2) {
        ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
        cell.lbl.text = @"Matches";
        cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
        cell.lbl.textColor =  [UIColor blackColor];
        cell.backgroundColor = [UIColor lightGrayColor];
        cell.rightArrowBtn.hidden = TRUE;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else if (indexPath.row <standingArray.count+3+fixturesArray.count){
        FixturesCell *cell = (FixturesCell*)[tableView dequeueReusableCellWithIdentifier:@"FixturesCell"];
        NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
        [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
        //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
        NSDate *matchDate = [dateformat dateFromString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"match_datetime"]];
        //        NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:matchDate];
        //        cell.date.text = [NSString stringWithFormat:@"%ld",[components day]];
        //        cell.month.text = [NSString stringWithFormat:@"%ld",[components month]];
        NSDateFormatter *df = [[NSDateFormatter alloc] init];
        
        [df setDateFormat:@"dd"];
        cell.date.text = [df stringFromDate:matchDate];
        
        [df setDateFormat:@"MMM"];
        cell.month.text = [df stringFromDate:matchDate];
//        cell.HomeTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+2)] valueForKey:@"HomeTeam"];
//        cell.AwayTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+2)] valueForKey:@"AwayTeam"];
        int homeTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homeScore"] intValue];
        int awayTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayScore"] intValue];
        if (homeTeamScore > awayTeamScore) {
            cell.HomeTeam.textColor = [UIColor redColor];
            cell.AwayTeam.textColor = [UIColor blackColor];
        }else if(homeTeamScore < awayTeamScore){
            cell.AwayTeam.textColor = [UIColor redColor];
            cell.HomeTeam.textColor = [UIColor blackColor];
        }else{
            cell.HomeTeam.textColor = [UIColor redColor];
            cell.AwayTeam.textColor = [UIColor redColor];
        }
        cell.HomeTeam.text = [NSString stringWithFormat:@"%d %@",homeTeamScore,[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"HomeTeam"]];
        cell.AwayTeam.text = [NSString stringWithFormat:@"%d %@",awayTeamScore,[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayTeam"]];
        cell.venue_name.text=[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"venue_name"];
        NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"match_number"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"round"]];
        cell.matchIDRound.text =matchIDRoundStr;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else{
        ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
        cell.lbl.text = @"View all club matches";
        cell.rightArrowBtn.hidden = FALSE;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    return 0;
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if(indexPath.row == 0){
        
    }
    else if (indexPath.row ==standingArray.count) {
        
    }else if (indexPath.row ==standingArray.count+1) {
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        LeagueDetailVC *myVC = (LeagueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"LeagueDetailVC"];
        myVC.leagueArray = standingArray;
        [self.navigationController pushViewController:myVC animated:YES];
        
    }else if(indexPath.row <standingArray.count+3+fixturesArray.count){
        NSLog(@"%@",[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)]);
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        MatchDetailVC *myVC = (MatchDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVC"];
        myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)];
        [self.navigationController pushViewController:myVC animated:YES];
    }else{
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AllMatchVC *myVC = (AllMatchVC *)[storyboard instantiateViewControllerWithIdentifier:@"AllMatchVC"];
        NSLog(@"%@",groupDetails);
        myVC.teamDetails = groupDetails;
        [self.navigationController pushViewController:myVC animated:YES];
    }
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
