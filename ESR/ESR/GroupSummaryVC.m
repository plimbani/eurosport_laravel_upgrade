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
#import "MatchDetailVCNew.h"
#import "AllMatchVC.h"
#import "LeagueDetailVC.h"
#import "UIColor+fromHex.h"
#import "NoDataCell.h"
#import "Reachability.h"
#import "GroupSummaryHeaderCell.h"
#import "UIColor+fromHex.h"
#import <QuartzCore/QuartzCore.h>

@interface GroupSummaryVC ()
 
@end

@implementation GroupSummaryVC
@synthesize groupDetails,selectedGroupStr;
@synthesize standingArray,fixturesArray;


- (void)viewDidLoad {
    [super viewDidLoad];
    tabSelectionStr = @"standing";
    if ([[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Elimination"]) {
        self.titlelbl.text = NSLocalizedString(@"PLACING MATCHES SUMMARY", @"");
    }else{
        self.titlelbl.text = NSLocalizedString(@"GROUP", @"");
    }
    //NSLog(@"%@",groupDetails);
    NSLog(@"%@",selectedGroupStr);
    // Do any additional setup after loading the view.
    [self.tableView flashScrollIndicators];
    
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        tabSelectionStr = @"match";
        [self getMatchFixtures];
    }else{
        [self getStanding];
    }
    UIGestureRecognizer *gestureRecognizer = [[UIGestureRecognizer alloc] init];
    gestureRecognizer.delegate = self;
    [self.tableView addGestureRecognizer:gestureRecognizer];
}

- (BOOL)gestureRecognizer:(UIGestureRecognizer *)gestureRecognizer shouldReceiveTouch:(UITouch *)touch
{
    self.pickerView.hidden = TRUE;
    return FALSE;
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
#pragma mark - TableView Delegate and DataSource
-(CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
    return 135;
}

-(UIView*)tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
    // 1. Dequeue the custom header cell
    GroupSummaryHeaderCell * headerCell = [tableView dequeueReusableCellWithIdentifier:@"GroupSummaryHeaderCell"];
    
    // 2. Set the label and button properties
    [headerCell.dropDownBtn addTarget:self action:@selector(dropDownBtnClick:) forControlEvents:UIControlEventTouchUpInside];
    [headerCell.matchBtn addTarget:self action:@selector(matchBtnClick:) forControlEvents:UIControlEventTouchUpInside];
    [headerCell.stendingBtn addTarget:self action:@selector(standingBtnClick:) forControlEvents:UIControlEventTouchUpInside];
    headerCell.selectedGroupTxtField.text = selectedGroupStr;
    headerCell.selectedGroupTxtField.leftView = [self PaddingView];
    headerCell.selectedGroupTxtField.leftViewMode = UITextFieldViewModeAlways;
    if ([tabSelectionStr isEqualToString:@"match"]) {
        if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
            headerCell.standingView.backgroundColor = [UIColor darkGrayColor];
            headerCell.stendingBtn.enabled = FALSE;
        }else{
            headerCell.standingView.backgroundColor = [UIColor whiteColor];
            headerCell.stendingBtn.enabled = TRUE;
        }
        headerCell.matchSeperaterView.backgroundColor = kThemeColor;
        headerCell.standingSeperaterView.backgroundColor = [UIColor clearColor];;
    }else{
        headerCell.stendingBtn.enabled = TRUE;
        headerCell.matchSeperaterView.backgroundColor = [UIColor clearColor];
        headerCell.standingSeperaterView.backgroundColor = kThemeColor;
    }
    // 3. And return
    return headerCell;
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    
    if ([tabSelectionStr isEqualToString:@"match"] ) {
        if (indexPath.row == 0) {
            return 50;
        }else{
            return 140 ;
        }
    }else{
        if(indexPath.row == 0){
            return 60;
        }else if (indexPath.row <=standingArray.count) {
            return 50 ;
        }else {
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
//    self.tableView.separatorColor = [UIColor lightGrayColor];
//    self.tableView.separatorStyle = UITableViewCellSeparatorStyleSingleLine;
    if ([tabSelectionStr isEqualToString:@"match"] ) {
        if (fixturesArray.count == 0) {
//            self.tableView.separatorColor = [UIColor clearColor];
//            self.tableView.separatorStyle = UITableViewCellSeparatorStyleNone;
            return 0;
        }
        return fixturesArray.count+1;
    }else{
        if (standingArray.count == 0) {
//            self.tableView.separatorColor = [UIColor clearColor];
//            self.tableView.separatorStyle = UITableViewCellSeparatorStyleNone;
            return 0;
        }
        return standingArray.count+2;
    }
    
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    if ([tabSelectionStr isEqualToString:@"match"] ) {
        
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
                    cell.homeTeamScore.textColor =[UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor blackColor];
                    cell.awayTeamScore.textColor = [UIColor blackColor];
                }else if(homeTeamScore < awayTeamScore){
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.HomeTeam.textColor = [UIColor blackColor];
                    cell.homeTeamScore.textColor = [UIColor blackColor];
                }else{
                    cell.HomeTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.AwayTeam.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.homeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                    cell.awayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
                }
                
                cell.HomeTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"HomeTeam"]];
                cell.AwayTeam.text = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"AwayTeam"]];
                cell.homeTeamScore.text = [NSString stringWithFormat:@"%d",homeTeamScore];
                cell.awayTeamScore.text = [NSString stringWithFormat:@"%d",awayTeamScore];
            }else{
                cell.HomeTeam.textColor = [UIColor blackColor];
                cell.AwayTeam.textColor = [UIColor blackColor];
                cell.homeTeamScore.text = @"";
                cell.awayTeamScore.text = @"";
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
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Home_id"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"] ) {
                    if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                        if ( [[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                            NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"competition_actual_name"];
                            if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                                cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homePlaceholder"]];
                            }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                                cell.HomeTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"homePlaceholder"]];
                            }
                        }else{
                            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                                cell.HomeTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"];
                            }
                            
                        }
                    }else{
                        cell.HomeTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayHomeTeamPlaceholderName"];
                    }
                    
                    cell.HomeTeam.textColor = [UIColor blackColor];
                }
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Away_id"] isKindOfClass:[NSNull class]]) {
                if ([[[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"] ) {
                    if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                        if ([[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                            NSString *competition_actual_name =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"competition_actual_name"];
                            if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                                cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayPlaceholder"]];
                            }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                                cell.AwayTeam.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"awayPlaceholder"]];
                            }
                        }else{
                            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                                cell.AwayTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"];
                            }
                        }
                    }else{
                        cell.AwayTeam.text =[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"displayAwayTeamPlaceholderName"];
                    }
                    
                    cell.AwayTeam.textColor = [UIColor blackColor];
                }
            }
//            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
//                NSString *vanueName = [NSString stringWithFormat:@"%@ - %@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"],[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"]];
//                cell.venue_name.text=vanueName;
//            }else{
//                cell.venue_name.text= @"";
//            }
            NSString *venueStr = NULL_STRING;
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"] isKindOfClass:[NSNull class]]) {
                venueStr = [NSString stringWithFormat:@"%@",[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"venue_name"]];
            }
            if (![[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
                venueStr = [NSString stringWithFormat:@"%@ - %@",venueStr,[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"pitch_number"]];
            }
            cell.venue_name.text = venueStr;
            
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
            
            NSString *isResultOverride = [ApplicationData getStringFromAnyType:[fixturesArray objectAtIndex:indexPath.row-1][@"isResultOverride"]];
            NSString *match_status = [ApplicationData getStringFromAnyType:[fixturesArray objectAtIndex:indexPath.row-1][@"match_status"]];
            NSString *match_winner = [ApplicationData getStringFromAnyType:[fixturesArray objectAtIndex:indexPath.row-1][@"match_winner"]];
            NSString *home_id = [ApplicationData getStringFromAnyType:[fixturesArray objectAtIndex:indexPath.row-1][@"Home_id"]];
            NSString *away_id = [ApplicationData getStringFromAnyType:[fixturesArray objectAtIndex:indexPath.row-1][@"Away_id"]];
            if ([isResultOverride length] > 0 && [isResultOverride intValue] == 1) {
                if ([match_status length] > 0 ) {
                    if ([match_winner length] > 0 && [home_id length] > 0 && [match_winner caseInsensitiveCompare:home_id] == NSOrderedSame) {
                        cell.homeTeamScore.text = [NSString stringWithFormat:@" %@*", cell.homeTeamScore.text];
                    } else if ([match_winner length] > 0 && [away_id length] > 0 && [match_winner caseInsensitiveCompare:away_id] == NSOrderedSame) {
                        cell.awayTeamScore.text = [NSString stringWithFormat:@" %@*", cell.awayTeamScore.text];
                    }
                }
            }
            
            NSString *matchIDRoundStr =[NSString stringWithFormat:@"%@ | %@ | %@",displayMatchNumber,[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"group_name"],[[fixturesArray objectAtIndex:indexPath.row-1] valueForKey:@"round"]];
            cell.matchIDRound.text =matchIDRoundStr;
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            return cell;
        }
    
    }else{
        if(indexPath.row == 0){
            StandingHeaderCell *cell = (StandingHeaderCell*)[tableView dequeueReusableCellWithIdentifier:@"StandingHeaderCell"];
            
            cell.titleLbl.text = [NSString stringWithFormat:@"%@ %@",[groupDetails valueForKey:@"display_name"],NSLocalizedString(@"league table", @"")];
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
        }else {
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
            
        }
    }
    return 0;
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
//    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
//        if (indexPath.row != 0) {
//            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//            MatchDetailVCNew *myVC = (MatchDetailVCNew *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVCNew"];
//            myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-1];
//            [self.navigationController pushViewController:myVC animated:YES];
//        }
//
//    }else{
//        if(indexPath.row == 0){
//            
//        }
//        else if (indexPath.row <=standingArray.count) {
//
//        }else if (indexPath.row ==standingArray.count+1) {
//            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//            LeagueDetailVC *myVC = (LeagueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"LeagueDetailVC"];
//            myVC.leagueArray = standingArray;
//            myVC.teamDetails = groupDetails;
//            [self.navigationController pushViewController:myVC animated:YES];
//        }else if(indexPath.row <standingArray.count+3+fixturesArray.count){
//            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//            MatchDetailVCNew *myVC = (MatchDetailVCNew *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVCNew"];
//            myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-(standingArray.count+3)];
//            [self.navigationController pushViewController:myVC animated:YES];
//        }else{
//            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//            AllMatchVC *myVC = (AllMatchVC *)[storyboard instantiateViewControllerWithIdentifier:@"AllMatchVC"];
//            myVC.teamDetails = groupDetails;
//            [self.navigationController pushViewController:myVC animated:YES];
//        }
//    }
    if ([tabSelectionStr isEqualToString:@"match"] ) {
        if (indexPath.row != 0) {
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            MatchDetailVCNew *myVC = (MatchDetailVCNew *)[storyboard instantiateViewControllerWithIdentifier:@"MatchDetailVCNew"];
            myVC.matchDetails = [fixturesArray objectAtIndex:indexPath.row-1];
            [self.navigationController pushViewController:myVC animated:YES];
        }
    }else{
        if (indexPath.row ==standingArray.count+1) {
            UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
            LeagueDetailVC *myVC = (LeagueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"LeagueDetailVC"];
            myVC.leagueArray = standingArray;
            myVC.teamDetails = groupDetails;
            [self.navigationController pushViewController:myVC animated:YES];
        }
    }
}
#pragma mark - Button Click
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (IBAction)dropDownBtnClick:(id)sender {
    self.pickerView.hidden = FALSE;
}
- (IBAction)matchBtnClick:(id)sender {
    tabSelectionStr = @"match";
    [self getMatchFixtures];
    //[self.tableView reloadData];
}
- (IBAction)standingBtnClick:(id)sender {
    tabSelectionStr = @"standing";
    [self getStanding];
    //[self.tableView reloadData];
}


#pragma mark - PickerView Delegate and DataSource
- (NSInteger )numberOfComponentsInPickerView:(UIPickerView *)pickerView
{
    return 1;
}
- (NSAttributedString *)pickerView:(UIPickerView *)pickerView attributedTitleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    NSString *title = [NSString stringWithFormat:@"%@", [[_pickerViewArray objectAtIndex:row] valueForKey:@"display_name"]];
    NSAttributedString *attString =
    [[NSAttributedString alloc] initWithString:title attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
    
    return attString;
}
// The number of rows of data
- (NSInteger )pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component
{
    return _pickerViewArray.count;
}

// The data to return for the row and component (column) that's being passed in
- (NSString*)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    return [NSString stringWithFormat:@"%@", [[_pickerViewArray objectAtIndex:row] valueForKey:@"display_name"]];
    
}
- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    
    selectedGroupStr = [[_pickerViewArray objectAtIndex:row] valueForKey:@"display_name"];
    groupDetails = [_pickerViewArray objectAtIndex:row];
    if (![[groupDetails valueForKey:@"competation_type"] isEqualToString:@"Round Robin"] && ![[groupDetails valueForKey:@"actual_competition_type"] isEqualToString:@"Round Robin"]) {
        tabSelectionStr = @"match";
        [self getMatchFixtures];
    }else{
        if ([tabSelectionStr isEqualToString:@"match"]) {
            tabSelectionStr = @"match";
            [self getMatchFixtures];
        }else{
            tabSelectionStr = @"standing";
            [self getStanding];
        }
    }
}

#pragma mark - function

-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
#pragma mark - API call

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
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      NSError *parseError = nil;
                                                      NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                      standingArray =[responseDictionary[@"data"] mutableCopy];
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
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetMatchFixtures];
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
                                                  
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      fixturesArray =[responseDictionary[@"data"] mutableCopy];
                                                      for (int i = 0; i<fixturesArray.count; i++) {
                                                          NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
                                                          [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
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
                                                      [self.tableView reloadData];
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }
}
@end
