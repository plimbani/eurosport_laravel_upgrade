//
//  AllMatchVC.m
//  ESR
//
//  Created by Aecor Digital on 05/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "AllMatchVC.h"
#import <AFNetworking/AFNetworking.h>
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "FixturesCell.h"
#import "MatchDetailVC.h"
#import  "QuartzCore/QuartzCore.h"
#import "UIColor+fromHex.h"
#import "Reachability.h"

@interface AllMatchVC ()

@end

@implementation AllMatchVC
@synthesize fixturesArray,teamDetails;
-(void)getMatchFixtures{
    
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSLog(@"%@",app.defaultTournamentDir);
        
        NSDictionary *dataDir = @{@"tournamentId":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"club_id":[NSString stringWithFormat:@"%@",[teamDetails valueForKey:@"club_id"]]};
        NSDictionary *params = @{@"tournamentData":dataDir };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetMatchFixturesClubID];
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
                                                  NSLog(@"%@",fixturesArray);
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.tableView reloadData];
                                                  });
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (void)viewDidLoad {
    [super viewDidLoad];
    NSLog(@"%@",teamDetails);
    self.teamNameLbl.text = [[teamDetails valueForKey:@"name"] uppercaseString];
    [self getMatchFixtures];
    
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _teamNameLbl.userInteractionEnabled = YES;
    [_teamNameLbl addGestureRecognizer:tapAction];
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
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}

- (void)lblClick:(UITapGestureRecognizer *)tapGesture {
    [self.navigationController popViewControllerAnimated:TRUE];
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
    
    return 135 ;
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return fixturesArray.count;
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
    FixturesCell *cell = (FixturesCell*)[tableView dequeueReusableCellWithIdentifier:@"FixturesCell"];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
        NSDate *matchDate = [dateformat dateFromString:[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"match_datetime"]];
        //        NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:matchDate];
        //        cell.date.text = [NSString stringWithFormat:@"%ld",[components day]];
        //        cell.month.text = [NSString stringWithFormat:@"%ld",[components month]];
        NSDateFormatter *df = [[NSDateFormatter alloc] init];
        
        [df setDateFormat:@"dd"];
        cell.date.text = [df stringFromDate:matchDate];
        
        [df setDateFormat:@"MMM"];
        cell.month.text = [df stringFromDate:matchDate];
    }
     if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"homeScore"]   isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
         int homeTeamScore =[[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"homeScore"] intValue];
         int awayTeamScore =[[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"AwayScore"] intValue];
         if (homeTeamScore > awayTeamScore) {
             cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
             cell.AwayTeam.textColor = [UIColor blackColor];
             cell.homeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
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
         cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"HomeTeam"]];
         cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"AwayTeam"]];
         cell.homeTeamScore.text = [NSString stringWithFormat:@"%d",homeTeamScore];
         cell.awayTeamScore.text = [NSString stringWithFormat:@"%d",awayTeamScore];
         if (cell.homeTeamScore.text.length ==2) {
             cell.awayTeamScore.text = [NSString stringWithFormat:@" %d",awayTeamScore];
         }
         if (cell.awayTeamScore.text.length ==2) {
             cell.homeTeamScore.text = [NSString stringWithFormat:@" %d",homeTeamScore];
         }
     }else{
         if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"HomeTeam"] isKindOfClass:[NSNull class]]) {
             cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"HomeTeam"]];
         }else{
             cell.HomeTeam.text = @"";
         }
         if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"AwayTeam"] isKindOfClass:[NSNull class]]) {
             cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"AwayTeam"]];
         }else{
             cell.AwayTeam.text = @"";
         }
         cell.AwayTeam.textColor = [UIColor blackColor];
         cell.HomeTeam.textColor = [UIColor blackColor];
     }
//    NSString *match_number = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"match_number"]];
//    if (match_number.length >12) {
//        match_number=[match_number substringToIndex:12];
//    }
    NSString *displayMatchNumber=[NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"displayMatchNumber"]];;
    if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                                                           withString:[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"displayHomeTeamPlaceholderName"]];
    }if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                           withString:[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"displayAwayTeamPlaceholderName"]];
    }
    if (displayMatchNumber.length >15) {
        displayMatchNumber=[displayMatchNumber substringToIndex:15];
    }
    NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",displayMatchNumber,[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"round"]];
    cell.matchIDRound.text =matchIDRoundStr;
    if (![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"pitch_number"]isKindOfClass:[NSNull class]]) {
        NSString *vanueName = [NSString stringWithFormat:@"%@ %@",[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"venue_name"],[[fixturesArray objectAtIndex:indexPath.row] valueForKey:@"pitch_number"]];
        cell.venue_name.text=vanueName;
    }else{
        cell.venue_name.text = @"";
    }
    
    if (cell.homeTeamScore.text.length == 2 || cell.awayTeamScore.text.length == 2) {
        cell.homeTeamScore.textAlignment = NSTextAlignmentRight;
        cell.awayTeamScore.textAlignment = NSTextAlignmentRight;
    }
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    return cell;
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    MatchDetailVC *myVC = (MatchDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVC"];
    myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row];
    [self.navigationController pushViewController:myVC animated:YES];
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
