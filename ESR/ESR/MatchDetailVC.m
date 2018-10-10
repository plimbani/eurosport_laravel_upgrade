//
//  MatchDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 03/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "MatchDetailVC.h"
#import "VenueDetailVC.h"
#import "MapVC.h"
#import "Reachability.h"
#import "Utils.h"
#import "UIColor+fromHex.h"

@interface MatchDetailVC ()

@end

@implementation MatchDetailVC
@synthesize matchDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.mainView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.mainView.layer.borderWidth = 2.0f;
    if (![[matchDetails valueForKey:@"homeScore"] isKindOfClass:[NSNull class]]) {
        _lblHomeTeamScore.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"homeScore"]];
    }else{
        _lblHomeTeamScore.text = NULL_STRING;
    }
    NSString *referreName;
    if (![[matchDetails valueForKey:@"first_name"] isKindOfClass:[NSNull class]]  ) {
        referreName  = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"first_name"]];
    }
    if (![[matchDetails valueForKey:@"last_name"] isKindOfClass:[NSNull class]]) {
        referreName  = [NSString stringWithFormat:@"%@ %@",referreName,[matchDetails valueForKey:@"last_name"]];
    }
    self.referreLbl.text = referreName;
    if (![[matchDetails valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
        _lblAwayTeamScore.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"AwayScore"]];
    }else{
        _lblAwayTeamScore.text = NULL_STRING;
    }
    
    if (![[matchDetails valueForKey:@"HomeTeam"] isKindOfClass:[NSNull class]]) {
         self.homeTeamName.text =[matchDetails valueForKey:@"HomeTeam"];
        self.homeTeamNameTextField.text =[matchDetails valueForKey:@"HomeTeam"];
    }else{
         self.homeTeamName.text =@"";
        self.homeTeamNameTextField.text =@"";
    }
    [self.homeTeamName sizeToFit];
    if (![[matchDetails valueForKey:@"AwayTeam"] isKindOfClass:[NSNull class]]) {
        self.awayTeamName.text =[matchDetails valueForKey:@"AwayTeam"];
        self.awayTeamNameTextField.text =[matchDetails valueForKey:@"AwayTeam"];

    }else{
        self.awayTeamName.text =@"";
        self.awayTeamNameTextField.text =@"";
    }
    [self.awayTeamName sizeToFit];
    if (![[matchDetails valueForKey:@"homeScore"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
        int homeTeamScore =[[matchDetails valueForKey:@"homeScore"] intValue];
        int awayTeamScore =[[matchDetails valueForKey:@"AwayScore"] intValue];
        if (homeTeamScore > awayTeamScore) {
            _lblHomeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            _lblAwayTeamScore.textColor = [UIColor blackColor];
            self.homeTeamNameTextField.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.awayTeamNameTextField.textColor = [UIColor blackColor];
        }else if(homeTeamScore < awayTeamScore){
            _lblAwayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            _lblHomeTeamScore.textColor = [UIColor blackColor];
            self.awayTeamNameTextField.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.homeTeamNameTextField.textColor = [UIColor blackColor];
        }else{
            _lblHomeTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];;
            _lblAwayTeamScore.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.homeTeamNameTextField.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];;
            self.awayTeamNameTextField.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
        }
        
    }
    NSString *temp =[NSString stringWithFormat:@"%@",@"@^^@"];
    if (![[matchDetails valueForKey:@"Home_id"] isKindOfClass:[NSNull class]] ) {
        if ([[[matchDetails valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"] ) {
            if (![[matchDetails valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[matchDetails valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[matchDetails valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        self.homeTeamNameTextField.text = [NSString stringWithFormat:@"%@%@",@"",[matchDetails valueForKey:@"homePlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        self.homeTeamNameTextField.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[matchDetails valueForKey:@"homePlaceholder"]];
                    }
                }else{
                    if (![[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                        self.homeTeamNameTextField.text =[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"];
                    }
                }
            }
            else{
                self.homeTeamNameTextField.text =[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"];
            }
            
        }
    }
    if (![[matchDetails valueForKey:@"Away_id"] isKindOfClass:[NSNull class]] ) {
        if ([[[matchDetails valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"] ) {
            if (![[matchDetails valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[matchDetails valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[matchDetails valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        self.awayTeamNameTextField.text = [NSString stringWithFormat:@"%@%@",@"",[matchDetails valueForKey:@"awayPlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        self.awayTeamNameTextField.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[matchDetails valueForKey:@"awayPlaceholder"]];
                    }
                }else{
                    if (![[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
                        self.awayTeamNameTextField.text =[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"];
                    }
                }
            }
            else{
                self.awayTeamNameTextField.text =[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"];
            }
            
        }
    }
    if (![[matchDetails valueForKey:@"HomeCountryName"] isKindOfClass:[NSNull class]]) {
        self.homeTeamCountry.text=[NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"HomeCountryName"]];
    }
    if (![[matchDetails valueForKey:@"AwayCountryName"] isKindOfClass:[NSNull class]]) {
        self.awayTeamCountry.text=[NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"AwayCountryName"]];
    }
    
    if (![[matchDetails valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
        NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
        [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
        //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
        NSDate *matchDate = [dateformat dateFromString:[matchDetails valueForKey:@"match_datetime"]];
        //        NSDateComponents *components = [[NSCalendar currentCalendar] components:NSCalendarUnitDay | NSCalendarUnitMonth | NSCalendarUnitYear fromDate:matchDate];
        //        cell.date.text = [NSString stringWithFormat:@"%ld",[components day]];
        //        cell.month.text = [NSString stringWithFormat:@"%ld",[components month]];
        NSDateFormatter *df = [[NSDateFormatter alloc] init];
        [df setDateFormat:@"dd"];
        NSString *dateStr = [df stringFromDate:matchDate];
        [df setDateFormat:@"MMMM"];
        NSString *monthStr = [df stringFromDate:matchDate];
        [df setDateFormat:@"HH:mm"];
        NSString *hoursStr = [df stringFromDate:matchDate];
        //self.dateTime.text =[matchDetails valueForKey:@"match_datetime"];
        
        self.dateTime.text =[NSString stringWithFormat:@"%@ %@  |  %@",dateStr,monthStr,hoursStr];
    }else{
        self.dateTime.text =@"";
    }
    
    self.groupName.text =[matchDetails valueForKey:@"group_name"];
    if (![[matchDetails valueForKey:@"match_status"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"MatchWinner"] isKindOfClass:[NSNull class]]) {
        _lblWinner.text = [NSString stringWithFormat:@"%@ - %@ %@",[matchDetails valueForKey:@"match_status"],[matchDetails valueForKey:@"MatchWinner"],@"is the winner"];
    }else{
        _winnerLblTopConstrain.constant = -50;
        _bottomViewTopConstrain.constant = 40+10;
        self.mainViewHeightConstrain.constant = 405+45;
    }
    if ([[matchDetails valueForKey:@"actual_round"] isEqualToString:@"Elimination"]) {
        if (![[matchDetails valueForKey:@"position"] isKindOfClass:[NSNull class]]) {
            
            self.positionLbl.text =[NSString stringWithFormat:@"Placing: %@", [matchDetails valueForKey:@"position"]];
        }else{
            
            self.positionLbl.text =@"Placing: N/A";
        }
        self.mainViewHeightConstrain.constant = 405+25+45;
    }else{
        self.middleTeamDetailViewHeightConstrain.constant = self.middleTeamDetailViewHeightConstrain.constant-25;
        self.mainViewHeightConstrain.constant = 405+45;
        self.positionLbl.text =@"";
    }
    
    NSString *displayMatchNumber=[NSString stringWithFormat:@"Match ID: %@",[matchDetails valueForKey:@"displayMatchNumber"]];;
    if (![[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                             withString:[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"]];
    }if (![[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                           withString:[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"]];
    }
    //self.matchId.text =[NSString stringWithFormat:@"Match ID: %@",[matchDetails valueForKey:@"match_number"]];
    self.matchId.text =displayMatchNumber;
    
    if (![[matchDetails valueForKey:@"AwayFlagLogo"] isKindOfClass:[NSNull class]]) {
        NSURL *url = [NSURL URLWithString:[matchDetails valueForKey:@"AwayFlagLogo"]];
        NSURLRequest* request = [NSURLRequest requestWithURL:url];
        [NSURLConnection sendAsynchronousRequest:request
                                           queue:[NSOperationQueue mainQueue]
                               completionHandler:^(NSURLResponse * response,
                                                   NSData * data,
                                                   NSError * error) {
                                   if (!error){
                                       UIImage *image = [UIImage imageWithData:data];
                                       self.awayTeamImage.image= image;
                                   }
                               }];
        self.mainViewHeightConstrain.constant = self.mainViewHeightConstrain.constant+10;

    }else{
        self.awayTeamWidthConstrain.constant = 40;
        self.awayTeamHeightConstrain.constant = 40;
        self.mainViewHeightConstrain.constant = self.mainViewHeightConstrain.constant+15;
    }
    
    if (![[matchDetails valueForKey:@"HomeFlagLogo"] isKindOfClass:[NSNull class]]) {
        NSURL *url1 = [NSURL URLWithString:[matchDetails valueForKey:@"HomeFlagLogo"]];
        NSURLRequest* request1 = [NSURLRequest requestWithURL:url1];
        [NSURLConnection sendAsynchronousRequest:request1
                                           queue:[NSOperationQueue mainQueue]
                               completionHandler:^(NSURLResponse * response,
                                                   NSData * data,
                                                   NSError * error) {
                                   if (!error){
                                       UIImage *image = [UIImage imageWithData:data];
                                       self.homeTeamImage.image= image;
                                   }
                               }];
    }else{
        self.homeTeamHeightConstrain.constant = 40;
        self.homeTeamImageWidthConstrain.constant = 40;
    }
    if (![[matchDetails valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
         NSString *venueText = [NSString stringWithFormat:@"%@ - %@",[matchDetails valueForKey:@"venue_name"],[matchDetails valueForKey:@"pitch_number"]];
        //[self.venueBtn setTitle:btnText  forState:UIControlStateNormal];
        self.venueLbl.text = venueText;
        UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
        tapAction.delegate =self;
        tapAction.numberOfTapsRequired = 1;
        
        //Enable the lable UserIntraction
        _titleLbl.userInteractionEnabled = YES;
        [_titleLbl addGestureRecognizer:tapAction];
    }else{
         [self.venueBtn setTitle:@""  forState:UIControlStateNormal];
    }
    if (![[matchDetails valueForKey:@"HomeTeamShirtColor"] isKindOfClass:[NSNull class]]) {
        self.homeTeamShirt.image = [self.homeTeamShirt.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.homeTeamShirt setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"HomeTeamShirtColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"HomeTeamShortsColor"] isKindOfClass:[NSNull class]]) {
        self.homeTeamShort.image = [self.homeTeamShort.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.homeTeamShort setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"HomeTeamShortsColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"AwayTeamShirtColor"] isKindOfClass:[NSNull class]]) {
        self.awayTeamShirt.image = [self.awayTeamShirt.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.awayTeamShirt setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"AwayTeamShirtColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"AwayTeamShortsColor"] isKindOfClass:[NSNull class]]) {
        self.awayTeamShort.image = [self.awayTeamShort.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.awayTeamShort setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"AwayTeamShortsColor"] alpha:1.0]];
    }
    [self setTeamScore:matchDetails];
    
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

- (void)setTeamScore:(NSDictionary *)dicFixture {
    NSString *isResultOverride = [ApplicationData getStringFromAnyType:dicFixture[@"isResultOverride"]];
    NSString *match_status = [ApplicationData getStringFromAnyType:dicFixture[@"match_status"]];
    NSString *match_winner = [ApplicationData getStringFromAnyType:dicFixture[@"match_winner"]];
    NSString *home_id = [ApplicationData getStringFromAnyType:dicFixture[@"Home_id"]];
    NSString *away_id = [ApplicationData getStringFromAnyType:dicFixture[@"Away_id"]];
    
    NSLog(@"Main Height = %f",_mainViewHeightConstrain.constant);
    
    if ([isResultOverride length] > 0 && [isResultOverride intValue] == 1) {
        if ([match_status length] > 0 && [match_status caseInsensitiveCompare:@"Walk-over"] == NSOrderedSame) {
            if ([match_winner length] > 0 && [home_id length] > 0 && [match_winner caseInsensitiveCompare:home_id] == NSOrderedSame) {
                _lblHomeTeamScore.text = [NSString stringWithFormat:@" %@*", _lblHomeTeamScore.text];
                _lblWinner.text = @"* Walkover win";
                _mainViewHeightConstrain.constant = _mainViewHeightConstrain.constant + 40 + 40;
            } else if ([match_winner length] > 0 && [away_id length] > 0 && [match_winner caseInsensitiveCompare:away_id] == NSOrderedSame) {
                _lblAwayTeamScore.text = [NSString stringWithFormat:@" %@*", _lblAwayTeamScore.text];
                _lblWinner.text = @"* Walkover win";
                _mainViewHeightConstrain.constant = _mainViewHeightConstrain.constant + 40 + 40;
            }
        }
    }
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

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)venueBtnClick:(id)sender {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    VenueDetailVC *myVC = (VenueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"VenueDetailVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];

}
@end
