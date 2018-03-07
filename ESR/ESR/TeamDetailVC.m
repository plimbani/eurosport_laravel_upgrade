//
//  TeamDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "TeamDetailVC.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
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
#import "LeagueDetailVC.h"
#import "AllMatchVC.h"
#import "UIColor+fromHex.h"
#import "NoDataCell.h"
#import "Reachability.h"
#import "AgeGroupVC.h"

@interface TeamDetailVC ()

@end

@implementation TeamDetailVC
@synthesize teamDetails;
@synthesize standingArray,fixturesArray;

-(void)getStanding{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competitionId":[teamDetails valueForKey:@"GroupId"]};
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
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"teamId":[teamDetails valueForKey:@"id"],@"is_scheduled":@"1" };
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
                                                  
//                                                  _tournamentlistArray =[responseDictionary[@"data"] mutableCopy];
                                                  for (int i = 0; i<fixturesArray.count; i++) {
                                                      NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
                                                      [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
                                                      //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
                                                      NSDate *startDate = [dateformat dateFromString:[NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:i] valueForKey:@"match_datetime"]]];
                                                      NSDictionary *dir =[[fixturesArray objectAtIndex:i] mutableCopy];
                                                      [dir setValue:startDate forKey:@"start_date"];
                                                      [fixturesArray replaceObjectAtIndex:i withObject:dir];
                                                      //[sort addObject:startDate];
                                                  }
                                                  NSSortDescriptor* sortByDate = [NSSortDescriptor sortDescriptorWithKey:@"match_datetime" ascending:YES];
                                                  [fixturesArray sortUsingDescriptors:[NSArray arrayWithObject:sortByDate]];
                                                  for (int i=0; i<fixturesArray.count; i++) {
                                                      NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
                                                      [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
                                                      NSString *startDate = [dateformat stringFromDate:[[fixturesArray objectAtIndex:i] valueForKey:@"start_date"]];
                                                      NSDictionary *dir =[[fixturesArray objectAtIndex:i] mutableCopy];
                                                      [dir setValue:startDate forKey:@"start_date"];
                                                      [fixturesArray replaceObjectAtIndex:i withObject:dir];
                                                      
                                                  }
                                                  
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
    //NSLog(@"%@",teamDetails);
    // Do any additional setup after loading the view.
    [self getMatchFixtures];
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
    self.titleLbl.text = NSLocalizedString(@"TEAM", @"");
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    self.tabBarController.tabBar.hidden = FALSE;
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.orientationFlag = @"0";
    [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
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

    if (indexPath.row == 0) {
        return 145;
    }else if(indexPath.row == 1){
        return 60;
    }else if (indexPath.row <=standingArray.count+1) {
        return 60 ;
    }else if (indexPath.row <=standingArray.count+2) {
        return 60 ;
    }else if(indexPath.row == standingArray.count +3){
        return 50;
    }else if(indexPath.row == standingArray.count +4){
        return 50;
    }else if (indexPath.row <standingArray.count+5+fixturesArray.count){
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
    NSInteger i =6;
    if (fixturesArray.count == 0) {
        i=  1+i-1;
    }
    if (standingArray.count == 0) {
        i = 1+i-1;
    }
    
    return fixturesArray.count+standingArray.count+i;
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
    if (indexPath.row == 0) {
        TeamDetailCell *cell = (TeamDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"TeamDetailCell"];
        cell.teamName.text = [teamDetails valueForKey:@"name"];
        cell.contryName.text = [teamDetails valueForKey:@"CountryName"];
        NSURL *url = [NSURL URLWithString:[teamDetails valueForKey:@"countryLogo"]];
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
        cell.groupName.text= [NSString stringWithFormat:@"%@, %@",[teamDetails valueForKey:@"ageGroupName"],[teamDetails valueForKey:@"group_name"]];
       // cell.groupName.text = [teamDetails valueForKey:@"group_name"];
        cell.view.layer.cornerRadius = 0;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else if(indexPath.row == 1){
        StandingHeaderCell *cell = (StandingHeaderCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingHeaderCell"];
//        for (int i=0; i<standingArray.count; i++) {
//            if([[teamDetails valueForKey:@"name"]isEqualToString:[[standingArray objectAtIndex:i] valueForKey:@"name"]]){
//                cell.title.text =[NSString stringWithFormat:@"%@ %@",[teamDetails valueForKey:@"group_name"],NSLocalizedString(@"league table", @"")];
//            }
//        }
        cell.title.text =[NSString stringWithFormat:@"%@ %@",[teamDetails valueForKey:@"group_name"],NSLocalizedString(@"league table", @"")];
        //cell.title.text= [[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"group_name"];
        //cell.backgroundColor = [UIColor lightGrayColor];
        cell.backgroundColor = [UIColor colorwithHexString:@"#DEDFE2" alpha:1.0];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }
    else if (indexPath.row <=standingArray.count+1) {
        if (standingArray.count != 0) {
            StandingCell *cell = (StandingCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingCell"];
            cell.name.text = [[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"name"];
            cell.played.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"played"]];
            cell.points.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"points"]];
            int differance = [[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"goal_for"] intValue] -[[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"goal_against"] intValue];
            if (differance > 0) {
                cell.plusMinus.text = [NSString stringWithFormat:@"%@%d",@"+",differance];
            }else{
                cell.plusMinus.text = [NSString stringWithFormat:@"%d",differance];
            }
            if (![[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"team_id"] isKindOfClass:[NSNull class]]) {
                if([[[teamDetails valueForKey:@"id"] stringValue]isEqualToString:[[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"team_id"] stringValue]]){
                    cell.backgroundColor = [UIColor colorwithHexString:@"c5dba7" alpha:1.0];
                }
            }
            
            if (![[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"teamFlag"] isKindOfClass:[NSNull class]]) {
                NSURL *url = [NSURL URLWithString:[[standingArray objectAtIndex:indexPath.row-2] valueForKey:@"teamFlag"]];
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
                cell.imagewidthConstrain.constant = 25;
                cell.imageHeightConstrain.constant = 25;
            }
            
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else{
            NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
            cell.lbl.text = @"No data";
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
    }else if (indexPath.row ==standingArray.count+2) {
        if (standingArray.count == 0) {
            NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
            cell.lbl.text = NSLocalizedString(@"No league data available", @"");
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else{
            ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
            cell.lbl.text =NSLocalizedString(@"Group details", @"");
            cell.rightArrowBtn.hidden = FALSE;
            cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
            cell.backgroundColor = [UIColor whiteColor];
            cell.lbl.textColor = [UIColor colorwithHexString:@"6EA724" alpha:1.0];
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
        
    }else if (indexPath.row ==standingArray.count+3) {
        ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
        cell.lbl.text =NSLocalizedString(@"Match schedule", @"");
        cell.rightArrowBtn.hidden = FALSE;
        cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
        cell.backgroundColor = [UIColor whiteColor];
        cell.lbl.textColor = [UIColor colorwithHexString:@"6EA724" alpha:1.0];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
        
    }else if (indexPath.row ==standingArray.count+4) {
        ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
        cell.lbl.text = NSLocalizedString(@"Matches", @"");
        //cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
        cell.lbl.textColor =  [UIColor blackColor];
        //cell.backgroundColor = [UIColor lightGrayColor];
        cell.backgroundColor = [UIColor colorwithHexString:@"#DEDFE2" alpha:1.0];
        cell.rightArrowBtn.hidden = TRUE;
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        return cell;
    }else if (indexPath.row <standingArray.count+5+fixturesArray.count){
        if (fixturesArray.count == 0) {
            
            NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
            cell.lbl.text = NSLocalizedString(@"No matches available", @"");
            cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
            
            
        }else{
            FixturesCell *cell = (FixturesCell*)[tableView dequeueReusableCellWithIdentifier:@"FixturesCell"];
            NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
            [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
            //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
                NSDate *matchDate = [dateformat dateFromString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"match_datetime"]];
                //        NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:matchDate];
                //        cell.date.text = [NSString stringWithFormat:@"%ld",[components day]];
                //        cell.month.text = [NSString stringWithFormat:@"%ld",[components month]];
                NSDateFormatter *df = [[NSDateFormatter alloc] init];
                [df setDateFormat:@"dd"];
                cell.date.text = [df stringFromDate:matchDate];
                [df setDateFormat:@"MMM"];
                cell.month.text = [df stringFromDate:matchDate];
            }
            
            
//            cell.HomeTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+4)] valueForKey:@"HomeTeam"];
//            cell.AwayTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+4)] valueForKey:@"AwayTeam"];
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homeScore"]   isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
                int homeTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homeScore"] intValue];
                int awayTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"AwayScore"] intValue];
                if (homeTeamScore > awayTeamScore) {
                    cell.homeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor blackColor];
                    cell.awayTeamScore.textColor = [UIColor blackColor];
                }else if(homeTeamScore < awayTeamScore){
                    
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.HomeTeam.textColor = [UIColor blackColor];
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.homeTeamScore.textColor = [UIColor blackColor];
                }else{
                    cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];;
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.homeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];;
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                }
                
                cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"HomeTeam"]];
                cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"AwayTeam"]];
                
                cell.homeTeamScore.text = [NSString stringWithFormat:@"%d",homeTeamScore];
                cell.awayTeamScore.text = [NSString stringWithFormat:@"%d",awayTeamScore];
                if (cell.homeTeamScore.text.length ==2) {
                    cell.awayTeamScore.text = [NSString stringWithFormat:@" %d",awayTeamScore];
                }
                if (cell.awayTeamScore.text.length ==2) {
                    cell.homeTeamScore.text = [NSString stringWithFormat:@" %d",homeTeamScore];
                }
            }else{
                cell.homeTeamScore.text = @"";
                cell.awayTeamScore.text = @"";
                cell.awayTeamScore.textColor = [UIColor blackColor];
                cell.homeTeamScore.textColor = [UIColor blackColor];
                cell.AwayTeam.textColor = [UIColor blackColor];
                cell.HomeTeam.textColor = [UIColor blackColor];
                if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"HomeTeam"] isKindOfClass:[NSNull class]]) {
                    cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"HomeTeam"]];
                }else{
                    cell.HomeTeam.text = @"";
                }
                if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"AwayTeam"] isKindOfClass:[NSNull class]]) {
                    cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"AwayTeam"]];
                }else{
                    cell.AwayTeam.text = @"";
                }
            }
            NSString *temp =[NSString stringWithFormat:@"%@",@"@^^@"];
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"Home_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"]  && [[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homePlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"homePlaceholder"]];
                    }
                    cell.HomeTeam.textColor = [UIColor blackColor];
                }
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"Away_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"]  && [[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"awayPlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"awayPlaceholder"]];
                    }
                    cell.AwayTeam.textColor = [UIColor blackColor];
                }
            }
            NSString *displayMatchNumber=[NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"displayMatchNumber"]];;
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"displayHomeTeamPlaceholderName"]];
            }if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"displayAwayTeamPlaceholderName"]];
            }
//            self.matchId.text =[NSString stringWithFormat:@"Match ID: %@",[matchDetails valueForKey:@"match_number"]];
//            self.matchId.text =displayMatchNumber;
//            NSString *match_number = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+4)] valueForKey:@"match_number"]];
//            if (match_number.length >12) {
//                match_number=[match_number substringToIndex:12];
//            }
            
            if (displayMatchNumber.length >15) {
                displayMatchNumber=[displayMatchNumber substringToIndex:15];
            }
            
            NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",displayMatchNumber,[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"round"]];
            cell.matchIDRound.text =matchIDRoundStr;
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
                NSString *vanueName = [NSString stringWithFormat:@"%@ - %@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"venue_name"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)] valueForKey:@"pitch_number"]];
                cell.venue_name.text=vanueName;
            }else{
                cell.venue_name.text= @"";
            }
            if (cell.homeTeamScore.text.length == 2 || cell.awayTeamScore.text.length == 2) {
                cell.homeTeamScore.textAlignment = NSTextAlignmentRight;
                cell.awayTeamScore.textAlignment = NSTextAlignmentRight;
            }
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
    }else{
        if (fixturesArray.count !=0) {
            ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
            cell.lbl.text = NSLocalizedString(@"View all club matches", @"");
            cell.rightArrowBtn.hidden = FALSE;
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            cell.backgroundColor = [UIColor whiteColor];
            cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
            cell.lbl.textColor = [UIColor colorwithHexString:@"6EA724" alpha:1.0];
            return cell;
        }else{
            NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
            cell.lbl.text = NSLocalizedString(@"No matches available", @"");
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
    }
    return 0;
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row == 0) {
        
    }else if(indexPath.row == 1){
        
    }else if (indexPath.row <=standingArray.count+1) {
        
    }else if (indexPath.row ==standingArray.count+2) {
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.selectedTab = @"1";
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        LeagueDetailVC *myVC = (LeagueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"LeagueDetailVC"];
        myVC.leagueArray = standingArray;
        myVC.teamDetails = teamDetails;
        [self.navigationController pushViewController:myVC animated:YES];
    }else if (indexPath.row ==standingArray.count+3) {
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.competationFormatId = [teamDetails valueForKey:@"age_group_id"];;
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AgeGroupVC *myVC = (AgeGroupVC *)[storyboard instantiateViewControllerWithIdentifier:@"AgeGroupVC"];
        [self.navigationController pushViewController:myVC animated:YES];
    }else if (indexPath.row ==standingArray.count+4) {
        
    }else if(indexPath.row <standingArray.count+5+fixturesArray.count){
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        MatchDetailVC *myVC = (MatchDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVC"];
        myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-(standingArray.count+5)];
        [self.navigationController pushViewController:myVC animated:YES];
    }else{
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        AllMatchVC *myVC = (AllMatchVC *)[storyboard instantiateViewControllerWithIdentifier:@"AllMatchVC"];
        myVC.teamDetails = teamDetails;
        [self.navigationController pushViewController:myVC animated:YES];
    }
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    [super preferredInterfaceOrientationForPresentation ];
    return UIInterfaceOrientationPortrait;
}
- (BOOL)shouldAutorotate
{
    [super shouldAutorotate];
    return NO;
}

- (UIInterfaceOrientationMask)supportedInterfaceOrientations
{[super supportedInterfaceOrientations];
    return UIInterfaceOrientationMaskPortrait;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    
    return UIInterfaceOrientationPortrait;
}
@end
