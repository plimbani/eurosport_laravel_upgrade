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
#import "UIColor+fromHex.h"
#import "NoDataCell.h"
#import "Reachability.h"

@interface GroupSummaryVC ()
 
@end

@implementation GroupSummaryVC
@synthesize groupDetails;
@synthesize standingArray,fixturesArray;
-(void)getStanding{
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competitionId":[groupDetails valueForKey:@"id"]};
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
//                                                  NSLog(@"data%@",data);
//                                                  NSLog(@"response%@",error);
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
        
//        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competitionId":[groupDetails valueForKey:@"id"],@"is_scheduled":@"1" };
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"competitionId":[groupDetails valueForKey:@"id"] };
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
    
    if ([[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Elimination"]) {
        self.titlelbl.text = NSLocalizedString(@"PLACING MATCHES SUMMARY", @"");
    }else{
        self.titlelbl.text = NSLocalizedString(@"GROUP", @"");
    }
    NSLog(@"%@",groupDetails);
    // Do any additional setup after loading the view.
    [self.tableView flashScrollIndicators];
    [self getMatchFixtures];
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
    if ([[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Elimination"]) {
        self.titlelbl.text = NSLocalizedString(@"PLACING MATCHES SUMMARY", @"");
    }else{
        self.titlelbl.text = NSLocalizedString(@"GROUP SUMMARY", @"");
    }
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
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
    
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        if (indexPath.row == 0) {
            return 50;
        }else{
            return 135 ;
        }
    }else{
        if(indexPath.row == 0){
            return 60;
        }else if (indexPath.row <=standingArray.count) {
            return 50 ;
        }else if(indexPath.row == standingArray.count +1){
            return 60;
        }else if(indexPath.row == standingArray.count +2){
            return 50;
        }else if (indexPath.row <standingArray.count+3+fixturesArray.count){
            return 135 ;
        }else{
            return 60;
        }
    }
    
    return 0;
  
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if (fixturesArray.count ==0 && standingArray.count == 0) {
        self.tableView.separatorColor = [UIColor clearColor];
        self.tableView.separatorStyle = UITableViewCellSeparatorStyleNone;
        return 0;
    }
    
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        self.tableView.separatorColor = [UIColor grayColor];
        self.tableView.separatorStyle = UITableViewCellSeparatorStyleSingleLine;
        return fixturesArray.count+1;
    }else{
        NSInteger i =3;
        if (fixturesArray.count == 0) {
            i=  1+i;
        }
        if (standingArray.count == 0) {
            i = 1+i;
        }
        NSLog(@"%ld",fixturesArray.count+standingArray.count+i);
        self.tableView.separatorColor = [UIColor grayColor];
        self.tableView.separatorStyle = UITableViewCellSeparatorStyleSingleLine;
        return fixturesArray.count+standingArray.count+i;
    }
    
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
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        if (indexPath.row ==0) {
            ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
            cell.lbl.text = NSLocalizedString(@"Game information", @"");
            //cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
            cell.lbl.textColor =  [UIColor blackColor];
            //cell.backgroundColor = [UIColor lightGrayColor];
            cell.backgroundColor = [UIColor colorwithHexString:@"#DEDFE2" alpha:1.0];
            cell.rightArrowBtn.hidden = TRUE;
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else {
            FixturesCell *cell = (FixturesCell*)[tableView dequeueReusableCellWithIdentifier:@"FixturesCell"];
            NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
            [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
            //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
                NSDate *matchDate = [dateformat dateFromString:[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"match_datetime"]];
                NSDateFormatter *df = [[NSDateFormatter alloc] init];
                
                [df setDateFormat:@"dd"];
                cell.date.text = [df stringFromDate:matchDate];
                
                [df setDateFormat:@"MMM"];
                cell.month.text = [df stringFromDate:matchDate];
            }else{
                cell.date.text =@"";
                cell.month.text = @"";
            }
            
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeScore"]   isKindOfClass:[NSNull class]] || ![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
                int homeTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeScore"] intValue];
                int awayTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayScore"] intValue];
                if (homeTeamScore > awayTeamScore) {
                    cell.HomeTeam.textColor =[UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor blackColor];
                }else if(homeTeamScore < awayTeamScore){
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.HomeTeam.textColor = [UIColor blackColor];
                }else{
                    cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                }
                cell.HomeTeam.text = [NSString stringWithFormat:@"%d %@",homeTeamScore,[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"HomeTeam"]];
                cell.AwayTeam.text = [NSString stringWithFormat:@"%d %@",awayTeamScore,[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayTeam"]];
            }else{
                cell.HomeTeam.textColor = [UIColor blackColor];
                cell.AwayTeam.textColor = [UIColor blackColor];
                if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"HomeTeam"] isKindOfClass:[NSNull class]]) {
                    cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"HomeTeam"]];
                }else{
                    cell.HomeTeam.text = @"";
                }
                if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayTeam"] isKindOfClass:[NSNull class]]) {
                    cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayTeam"]];
                }else{
                    cell.AwayTeam.text = @"";
                }
                
            }
            NSString *temp =[NSString stringWithFormat:@"%@",@"@^^@"];
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Home_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"] ) {
                    if ( [[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                        NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"competition_actual_name"];
                        if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                            cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homePlaceholder"]];
                        }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                            cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homePlaceholder"]];
                        }
                    }else{
                        cell.HomeTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"];
                    }
                    
                    cell.HomeTeam.textColor = [UIColor blackColor];
                }
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Away_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"] ) {
                    if ([[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                        NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"competition_actual_name"];
                        if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                            cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayPlaceholder"]];
                        }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                            cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayPlaceholder"]];
                        }
                    }else{
                        cell.AwayTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"];
                    }
                    
                    cell.AwayTeam.textColor = [UIColor blackColor];
                }
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
                NSString *vanueName = [NSString stringWithFormat:@"%@ - %@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"],[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"]];
                cell.venue_name.text=vanueName;
            }else{
                cell.venue_name.text= @"";
            }
            
            
            NSString *displayMatchNumber=[NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayMatchNumber"]];;
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"]];
            }if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"]];
            }
            if (displayMatchNumber.length >15) {
                displayMatchNumber=[displayMatchNumber substringToIndex:15];
            }
//            NSString *match_number = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"match_number"]];
//            if (match_number.length >12) {
//                match_number=[match_number substringToIndex:12];
//            }
            NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",displayMatchNumber,[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"round"]];
            cell.matchIDRound.text =matchIDRoundStr;
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
    }else{
        if(indexPath.row == 0){
            StandingHeaderCell *cell = (StandingHeaderCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingHeaderCell"];
            
            cell.titleLbl.text = [NSString stringWithFormat:@"%@ %@",[groupDetails valueForKey:@"name"],NSLocalizedString(@"league table", @"")];
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            cell.backgroundColor = [UIColor colorwithHexString:@"DEDFE2" alpha:1.0];
            return cell;
        }
        else if (indexPath.row <=standingArray.count) {
            StandingCell *cell = (StandingCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingCell"];
            cell.name.text = [[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"name"];
            cell.played.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"played"]];
            cell.points.text = [NSString stringWithFormat:@"%@",[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"points"]];
            int differance = [[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_for"] intValue] -[[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"goal_against"] intValue];
            if (differance > 0) {
                cell.plusMinus.text = [NSString stringWithFormat:@"%@%d",@"+",differance];
            }else{
                cell.plusMinus.text = [NSString stringWithFormat:@"%d",differance];
            }
            if (![[[standingArray objectAtIndex:indexPath.row-1] valueForKey:@"teamFlag"] isKindOfClass:[NSNull class]]) {
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
            }else{
                cell.imagewidthConstrain.constant = 25;
                cell.imageHeightConstrain.constant = 25;
            }
            
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else if (indexPath.row ==standingArray.count+1) {
            if (standingArray.count == 0) {
                NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
                cell.lbl.text = NSLocalizedString(@"No leagues available", @"");
                cell.selectionStyle = UITableViewCellSelectionStyleNone;
                return cell;
            }else{
                ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
                cell.lbl.text = NSLocalizedString(@"Group details", @"");
                cell.rightArrowBtn.hidden = FALSE;
                cell.backgroundColor = [UIColor whiteColor];
                cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
                cell.lbl.textColor = [UIColor colorwithHexString:@"6EA724" alpha:1.0];
                cell.selectionStyle = UITableViewCellSelectionStyleNone;
                return cell;
            }
            
        }else if (indexPath.row ==standingArray.count+2) {
            ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
            cell.lbl.text = NSLocalizedString(@"Game information", @"");
            //cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
            cell.lbl.textColor =  [UIColor blackColor];
            //cell.backgroundColor = [UIColor lightGrayColor];
            cell.backgroundColor = [UIColor colorwithHexString:@"#DEDFE2" alpha:1.0];
            cell.rightArrowBtn.hidden = TRUE;
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else if (indexPath.row <standingArray.count+3+fixturesArray.count){
            FixturesCell *cell = (FixturesCell*)[tableView dequeueReusableCellWithIdentifier:@"FixturesCell"];
            NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
            [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
            //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
                NSDate *matchDate = [dateformat dateFromString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"match_datetime"]];
                //        NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:matchDate];
                //        cell.date.text = [NSString stringWithFormat:@"%ld",[components day]];
                //        cell.month.text = [NSString stringWithFormat:@"%ld",[components month]];
                NSDateFormatter *df = [[NSDateFormatter alloc] init];
                
                [df setDateFormat:@"dd"];
                cell.date.text = [df stringFromDate:matchDate];
                
                [df setDateFormat:@"MMM"];
                cell.month.text = [df stringFromDate:matchDate];
            }else{
                cell.date.text =@"";
                cell.month.text = @"";
            }
            
//            cell.arrowBtn.tag = indexPath.row;
//            [cell.arrowBtn addTarget:self action:@selector(handleButtonClick:) forControlEvents:UIControlEventTouchUpInside];
            
            //        cell.HomeTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+2)] valueForKey:@"HomeTeam"];
            //        cell.AwayTeam.text = [[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+2)] valueForKey:@"AwayTeam"];
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homeScore"]   isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
                int homeTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homeScore"] intValue];
                int awayTeamScore =[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayScore"] intValue];
                if (homeTeamScore > awayTeamScore) {
                    cell.HomeTeam.textColor =[UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor blackColor];
                    cell.homeTeamScore.textColor =[UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.awayTeamScore.textColor = [UIColor blackColor];
                }else if(homeTeamScore < awayTeamScore){
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.HomeTeam.textColor = [UIColor blackColor];
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.homeTeamScore.textColor = [UIColor blackColor];
                }else{
                    cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.homeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                }
                cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"HomeTeam"]];
                cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayTeam"]];
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
                if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"HomeTeam"] isKindOfClass:[NSNull class]]) {
                    cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"HomeTeam"]];
                }else{
                    cell.HomeTeam.text = @"";
                }
                if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayTeam"] isKindOfClass:[NSNull class]]) {
                    cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"AwayTeam"]];
                }else{
                    cell.AwayTeam.text = @"";
                }
                
            }
            
            
            NSString *temp =[NSString stringWithFormat:@"%@",@"@^^@"];
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"Home_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"] ) {
                    if ([[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                        NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"competition_actual_name"];
                        if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                            cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homePlaceholder"]];
                        }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                            cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"homePlaceholder"]];
                        }
                    }else{
                        cell.HomeTeam.text =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayHomeTeamPlaceholderName"];
                    }
                    
                    cell.HomeTeam.textColor = [UIColor blackColor];
                }
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"Away_id"] isKindOfClass:[NSNull class]]  && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"]  ) {
                    if ([[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                        NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"competition_actual_name"];
                        if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                            cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"awayPlaceholder"]];
                        }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                            cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"awayPlaceholder"]];
                        }
                    }else{
                        cell.AwayTeam.text =[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayAwayTeamPlaceholderName"];
                    }
                    
                    cell.AwayTeam.textColor = [UIColor blackColor];
                }
            }
            
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
                NSString *vanueName = [NSString stringWithFormat:@"%@ - %@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"venue_name"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"pitch_number"]];
                cell.venue_name.text=vanueName;
            }else{
                cell.venue_name.text=@"";
            }
            
            //cell.venue_name.text=[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"venue_name"];
//            NSString *match_number = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"match_number"]];
//            if (match_number.length >12) {
//                match_number=[match_number substringToIndex:12];
//            }
            NSString *displayMatchNumber=[NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayMatchNumber"]];;
            if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayHomeTeamPlaceholderName"]];
            }if (![[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                                   withString:[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"displayAwayTeamPlaceholderName"]];
            }
            if (displayMatchNumber.length >15) {
                displayMatchNumber=[displayMatchNumber substringToIndex:15];
            }
            NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",displayMatchNumber,[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)] valueForKey:@"round"]];
            cell.matchIDRound.text =matchIDRoundStr;
            if (cell.homeTeamScore.text.length == 2 || cell.awayTeamScore.text.length == 2) {
                cell.homeTeamScore.textAlignment = NSTextAlignmentRight;
                cell.awayTeamScore.textAlignment = NSTextAlignmentRight;
            }
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }else{
            if (fixturesArray.count ==0) {
                NoDataCell *cell = (NoDataCell*)[tableView dequeueReusableCellWithIdentifier:@"NoDataCell"];
                cell.lbl.text = NSLocalizedString(@"No matches available", @"");
                cell.selectionStyle = UITableViewCellSelectionStyleNone;
                return cell;
                
            }else{
                ViewDetailCell *cell = (ViewDetailCell*)[tableView dequeueReusableCellWithIdentifier:@"ViewDetailCell"];
                cell.lbl.text = NSLocalizedString(@"View all club matches", @"");
                cell.lbl.font = [UIFont boldSystemFontOfSize:17 ];
                cell.rightArrowBtn.hidden = FALSE;
                cell.selectionStyle = UITableViewCellSelectionStyleNone;
                cell.backgroundColor = [UIColor whiteColor];
                cell.lbl.textColor = [UIColor colorwithHexString:@"6EA724" alpha:1.0];
                return cell;
            }
        }
    }
    
    return 0;
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        if (indexPath.row != 0) {
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            MatchDetailVC *myVC = (MatchDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVC"];
            myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-1];
            [self.navigationController pushViewController:myVC animated:YES];
        }
        
    }else{
        if(indexPath.row == 0){
            
        }
        else if (indexPath.row <=standingArray.count) {
            
        }else if (indexPath.row ==standingArray.count+1) {
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            LeagueDetailVC *myVC = (LeagueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"LeagueDetailVC"];
            myVC.leagueArray = standingArray;
            myVC.teamDetails = groupDetails;
            [self.navigationController pushViewController:myVC animated:YES];
        }else if(indexPath.row <standingArray.count+3+fixturesArray.count){
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            MatchDetailVC *myVC = (MatchDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVC"];
            myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)];
            [self.navigationController pushViewController:myVC animated:YES];
        }else{
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            AllMatchVC *myVC = (AllMatchVC *)[storyboard instantiateViewControllerWithIdentifier:@"AllMatchVC"];
            myVC.teamDetails = groupDetails;
            [self.navigationController pushViewController:myVC animated:YES];
        }
    }
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
