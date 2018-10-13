//
//  MatchDetailVCNew.m
//  ESR
//
//  Created by GBarot on 25/09/18.
//  Copyright © 2018 Aecor Digital. All rights reserved.
//

#import "MatchDetailVCNew.h"
#import "Utils.h"
#import "UIColor+fromHex.h"
#import "VenueDetailVC.h"
#import "Reachability.h"

@interface MatchDetailVCNew ()

@end

@implementation MatchDetailVCNew
@synthesize matchDetails;
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    [self setData];
}
-(void)viewDidAppear:(BOOL)animated{

    [self setBorder:_viewVenue setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"left"];
    [self setBorder:_viewVenue setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"bottom"];
    [self setBorder:_viewVenue setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"right"];
    [self setBorder:_stackviewTop setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"top"];
    [self setBorder:_stackviewTop setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"left"];
    [self setBorder:_stackviewTop setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"right"];
    [self setBorder:_stackviewMiddle setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"left"];
    [self setBorder:_stackviewMiddle setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"right"];
    [self setBorder:_viewWinnerStatus setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"left"];
    [self setBorder:_viewWinnerStatus setColor:[UIColor lightGrayColor] setThickness:1.0 setType:@"right"];
    UIView* subView = [[UIView alloc] initWithFrame:_stackviewMiddle.bounds];
    
    subView.backgroundColor = [UIColor colorwithHexString:@"#DEDFE2" alpha:1.0];
    [_stackviewMiddle insertSubview:subView atIndex:0];
    [_stackviewMiddle updateConstraintsIfNeeded];
    
}
- (void)viewDidLayoutSubviews {
    
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable){
        self.offlineView.hidden = false;
        self.scrollViewTopConstrain.constant = 50;
    }
    else{
        self.offlineView.hidden = TRUE;
        self.scrollViewTopConstrain.constant = 0;
    }
}
-(void)viewWillAppear:(BOOL)animated{
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
        self.scrollViewTopConstrain.constant = 0;
    }else{
        self.offlineView.hidden = false;
        self.scrollViewTopConstrain.constant = 50;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
}
-(void)setData{
    int homeScore = -1;
    int awayScore = -1;
    if (![[matchDetails valueForKey:@"homeScore"] isKindOfClass:[NSNull class]]) {
        self.lblTeam1Score.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"homeScore"]];
        homeScore =[[matchDetails valueForKey:@"homeScore"] intValue];
    }else{
        self.lblTeam1Score.text = NULL_STRING;
    }
    if (![[matchDetails valueForKey:@"AwayScore"] isKindOfClass:[NSNull class]]) {
        self.lblTeam2Score.text = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"AwayScore"]];
        awayScore =[[matchDetails valueForKey:@"AwayScore"] intValue];
    }else{
        self.lblTeam2Score.text = NULL_STRING;
    }
    
    if (homeScore != -1 && awayScore != -1) {
        if (homeScore == awayScore) {
            self.lblTeam1Score.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam2Score.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam1Name.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam2Name.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
        }else if( homeScore > awayScore ){
            self.lblTeam1Score.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam2Score.textColor = [UIColor blackColor];
            self.lblTeam1Name.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam2Name.textColor = [UIColor blackColor];
        }else{
            self.lblTeam2Score.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam1Score.textColor = [UIColor blackColor];
            self.lblTeam2Name.textColor = [UIColor colorwithHexString:@"C70A20" alpha:1.0];
            self.lblTeam1Name.textColor = [UIColor blackColor];
        }
        
    }else{
        self.lblTeam2Score.textColor = [UIColor blackColor];
        self.lblTeam1Score.textColor = [UIColor blackColor];
        self.lblTeam2Name.textColor = [UIColor blackColor];
        self.lblTeam1Name.textColor = [UIColor blackColor];
    }
    
    // Home team name
    NSString *temp =[NSString stringWithFormat:@"%@",@"@^^@"];
    if (![[matchDetails valueForKey:@"Home_id"] isKindOfClass:[NSNull class]] ) {
        if ([[[matchDetails valueForKey:@"Home_id"] stringValue] isEqualToString:@"0"] ) {
            if (![[matchDetails valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[matchDetails valueForKey:@"homeTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[matchDetails valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        self.lblTeam1Name.text = [NSString stringWithFormat:@"%@%@",@"",[matchDetails valueForKey:@"homePlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        self.lblTeam1Name.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[matchDetails valueForKey:@"homePlaceholder"]];
                    }
                }else{
                    self.lblTeam1Name.text =[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"];
                }
            }
            else{
                if(![[matchDetails valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]){
                    self.lblTeam1Name.text =[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"];
                }else{
                    self.lblTeam1Name.text = NULL_STRING;
                }
                
            }
        }else{
            if(![[matchDetails valueForKey:@"homeTeamName"] isKindOfClass:[NSNull class]]){
                self.lblTeam1Name.text =[matchDetails valueForKey:@"homeTeamName"];
            }else{
                self.lblTeam1Name.text = NULL_STRING;
            }
        }
    }
    
    
    
    
    // Away team name
    
    if (![[matchDetails valueForKey:@"Away_id"] isKindOfClass:[NSNull class]] ) {
        if ([[[matchDetails valueForKey:@"Away_id"] stringValue] isEqualToString:@"0"] ) {
            if (![[matchDetails valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]) {
                if ([[matchDetails valueForKey:@"awayTeamName"] isEqualToString:temp]) {
                    NSString *competition_actual_name =[matchDetails valueForKey:@"competition_actual_name"];
                    if ([competition_actual_name rangeOfString:@"Group"] .location != NSNotFound) {
                        self.lblTeam2Name.text = [NSString stringWithFormat:@"%@%@",@"",[matchDetails valueForKey:@"awayPlaceholder"]];
                    }else if([competition_actual_name rangeOfString:@"Pos"] .location != NSNotFound){
                        self.lblTeam2Name.text = [NSString stringWithFormat:@"%@%@",@"Pos-",[matchDetails valueForKey:@"awayPlaceholder"]];
                    }else{
                        self.lblTeam2Name.text =[matchDetails valueForKey:@"awayTeamName"];
                    }
                }else{
                    self.lblTeam2Name.text =[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"];
                }
            }else{
                if(![[matchDetails valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]){
                    self.lblTeam2Name.text =[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"];
                }else{
                    self.lblTeam2Name.text = NULL_STRING;
                }
            }
        }else{
            if(![[matchDetails valueForKey:@"awayTeamName"] isKindOfClass:[NSNull class]]){
                self.lblTeam2Name.text =[matchDetails valueForKey:@"awayTeamName"];
            }else{
                self.lblTeam2Name.text = NULL_STRING;
            }
        }
    }
    
    NSString *referreName = NULL_STRING;
    if (![[matchDetails valueForKey:@"first_name"] isKindOfClass:[NSNull class]]  ) {
        referreName  = [NSString stringWithFormat:@"%@",[matchDetails valueForKey:@"first_name"]];
    }
    if (![[matchDetails valueForKey:@"last_name"] isKindOfClass:[NSNull class]]) {
        referreName  = [NSString stringWithFormat:@"%@ %@",referreName,[matchDetails valueForKey:@"last_name"]];
    }
    if (![referreName isEqualToString:NULL_STRING]) {
        self.lblRefereeName.text = referreName;
    }else{
        [_stackviewMiddle removeArrangedSubview:_lblRefereeName];
        [_lblRefereeName removeFromSuperview];
    }
    
    
    if (![[matchDetails valueForKey:@"match_status"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"MatchWinner"] isKindOfClass:[NSNull class]]) {
        _lblWinnerStatus.text = [NSString stringWithFormat:@"%@ - %@ %@",[matchDetails valueForKey:@"match_status"],[matchDetails valueForKey:@"MatchWinner"],@"is the winner"];
    }else{
        self.heightConstraintViewWinnerStatus.constant = 0;
        self.viewWinnerStatus.clipsToBounds = TRUE;
        [self.viewWinnerStatus updateConstraints];
    }
    
    
    
    // Image
    
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
                                       self.imgViewTeam1Flag.image= image;
                                   }
                               }];
    }else{
        
    }
    
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
                                       self.imgViewTeam2Flag.image= image;
                                   }
                               }];
    }else{
        
    }
    

    if (![[matchDetails valueForKey:@"match_datetime"] isKindOfClass:[NSNull class]]) {
        NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
        [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
        NSDate *matchDate = [dateformat dateFromString:[matchDetails valueForKey:@"match_datetime"]];
        NSDateFormatter *df = [[NSDateFormatter alloc] init];
        [df setDateFormat:@"dd"];
        NSString *dateStr = [df stringFromDate:matchDate];
        [df setDateFormat:@"MMMM"];
        NSString *monthStr = [df stringFromDate:matchDate];
        [df setDateFormat:@"YYYY"];
        NSString *yearStr = [df stringFromDate:matchDate];
        [df setDateFormat:@"HH:mm"];
        NSString *hoursStr = [df stringFromDate:matchDate];
        //self.dateTime.text =[NSString stringWithFormat:@"%@ %@  |  %@",dateStr,monthStr,hoursStr];
        self.lblDate.text = [NSString stringWithFormat:@"%@ %@ %@ %@",hoursStr,dateStr,monthStr,yearStr];
        //self.lblTime.text = [NSString stringWithFormat:@"%@",hoursStr];
    }else{
        self.lblDate.text = NULL_STRING;
        //self.lblTime.text = NULL_STRING;
        self.heightConstraintDateSeparator.constant = 0;
    }
    
    if (![[matchDetails valueForKey:@"group_name"] isKindOfClass:[NSNull class]]) {
        self.lblAgeNGroupInfo.text = [matchDetails valueForKey:@"group_name"];
    }else {
        [_stackviewMiddle removeArrangedSubview:_lblRefereeName];
        [_lblAgeNGroupInfo removeFromSuperview];
    }
    
    
    NSString *displayMatchNumber=[NSString stringWithFormat:@"Match ID: %@",[matchDetails valueForKey:@"displayMatchNumber"]];;
    if (![[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@HOME"
                                                                           withString:[matchDetails valueForKey:@"displayHomeTeamPlaceholderName"]];
    }if (![[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"] isKindOfClass:[NSNull class]]) {
        displayMatchNumber = [displayMatchNumber stringByReplacingOccurrencesOfString:@"@AWAY"
                                                                           withString:[matchDetails valueForKey:@"displayAwayTeamPlaceholderName"]];
    }
    self.lblMatchId.text = displayMatchNumber;
    
    
    if (![[matchDetails valueForKey:@"venue_name"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"pitch_number"] isKindOfClass:[NSNull class]]) {
        NSString *venueText = [NSString stringWithFormat:@"%@ - %@",[matchDetails valueForKey:@"venue_name"],[matchDetails valueForKey:@"pitch_number"]];
        //[self.venueBtn setTitle:btnText  forState:UIControlStateNormal];
        self.lblVenue.text = venueText;
        UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(onVenueViewPressed:)];
        tapAction.delegate =self;
        tapAction.numberOfTapsRequired = 1;
        
        //Enable the lable UserIntraction
        self.viewVenue.userInteractionEnabled = YES;
        [self.viewVenue addGestureRecognizer:tapAction];
    }else{
        //[self.lblVenue setTitle:@""  forState:UIControlStateNormal];
    }
    
    if ([[matchDetails valueForKey:@"actual_round"] isEqualToString:@"Elimination"]) {
        if (![[matchDetails valueForKey:@"position"] isKindOfClass:[NSNull class]]) {
            
            self.lblPlacing.text =[NSString stringWithFormat:@"Placing: %@", [matchDetails valueForKey:@"position"]];
        }else{
            
            self.lblPlacing.text =@"Placing: N/A";
        }
        
    }else{
        [self.stackviewMiddle removeArrangedSubview:_lblPlacing];
        [_lblPlacing removeFromSuperview];
    }

    [self setTeamScore:matchDetails];
    
    
    self.imgViewTeam1Tshirt.image = [UIImage imageNamed:@"tshirt"];
    self.imgViewTeam1Short.image = [UIImage imageNamed:@"short"];
    self.imgViewTeam2Tshirt.image = [UIImage imageNamed:@"tshirt"];
    self.imgViewTeam2Short.image = [UIImage imageNamed:@"short"];
    
    
    if (![[matchDetails valueForKey:@"HomeTeamShirtColor"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"HomeTeamShirtColor"] isEqualToString:@"#FFFFFF"]) {
        self.imgViewTeam1Tshirt.image = [self.imgViewTeam1Tshirt.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.imgViewTeam1Tshirt setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"HomeTeamShirtColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"HomeTeamShortsColor"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"HomeTeamShortsColor"] isEqualToString:@"#FFFFFF"]) {
        self.imgViewTeam1Short.image = [self.imgViewTeam1Short.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.imgViewTeam1Short setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"HomeTeamShortsColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"AwayTeamShirtColor"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"AwayTeamShirtColor"] isEqualToString:@"#FFFFFF"]) {
        self.imgViewTeam2Tshirt.image = [self.imgViewTeam2Tshirt.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.imgViewTeam2Tshirt setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"AwayTeamShirtColor"] alpha:1.0]];
    }
    if (![[matchDetails valueForKey:@"AwayTeamShortsColor"] isKindOfClass:[NSNull class]] && ![[matchDetails valueForKey:@"AwayTeamShortsColor"] isEqualToString:@"#FFFFFF"]) {
        self.imgViewTeam2Short.image = [self.imgViewTeam2Short.image imageWithRenderingMode:UIImageRenderingModeAlwaysTemplate];
        [self.imgViewTeam2Short setTintColor:[UIColor colorwithHexString:[matchDetails valueForKey:@"AwayTeamShortsColor"] alpha:1.0]];
    }
    
}
- (void)setTeamScore:(NSDictionary *)dicFixture {
    NSString *isResultOverride = [ApplicationData getStringFromAnyType:dicFixture[@"isResultOverride"]];
    NSString *match_status = [ApplicationData getStringFromAnyType:dicFixture[@"match_status"]];
    NSString *match_winner = [ApplicationData getStringFromAnyType:dicFixture[@"match_winner"]];
    NSString *home_id = [ApplicationData getStringFromAnyType:dicFixture[@"Home_id"]];
    NSString *away_id = [ApplicationData getStringFromAnyType:dicFixture[@"Away_id"]];
    
    //NSLog(@"Main Height = %f",_mainViewHeightConstrain.constant);
    
    if ([isResultOverride length] > 0 && [isResultOverride intValue] == 1) {
        if ([match_status length] > 0 ) {
            if ([match_winner length] > 0 && [home_id length] > 0 && [match_winner caseInsensitiveCompare:home_id] == NSOrderedSame) {
                self.lblTeam1Score.text = [NSString stringWithFormat:@" %@*", self.lblTeam1Score.text];
            } else if ([match_winner length] > 0 && [away_id length] > 0 && [match_winner caseInsensitiveCompare:away_id] == NSOrderedSame) {
                self.lblTeam2Score.text = [NSString stringWithFormat:@" %@*", self.lblTeam2Score.text];
            }
        }
        if([match_status caseInsensitiveCompare:@"Walk-over"]){
            _lblWinnerStatus.text = @"* Walkover, win awarded";
        }else if([match_status caseInsensitiveCompare:@"penalties"]){
            _lblWinnerStatus.text = @"* Game won on penalties";
        }else if ([match_status caseInsensitiveCompare:@"abandoned"]){
            _lblWinnerStatus.text = @"* Abandoned, win awarded";
        }
    }
}

-(void)setBorder:(UIView *)view setColor :(UIColor *)color setThickness:(CGFloat)thickness  setType: (NSString *)type{
    CALayer *sublayer = [CALayer layer];
    sublayer.borderWidth = thickness;
    if ([type isEqualToString:@"top"]) {
        sublayer.frame = CGRectMake(0, 0, view.frame.size.width, thickness);
    }else if ([type isEqualToString:@"bottom"]) {
        sublayer.frame = CGRectMake(0, view.frame.size.height - thickness, view.frame.size.width, view.frame.size.height);
    }else if ([type isEqualToString:@"left"]) {
        sublayer.frame = CGRectMake(0, 0, thickness, view.frame.size.height);
    }else if ([type isEqualToString:@"right"]) {
        sublayer.frame = CGRectMake(view.frame.size.width - thickness, 0, thickness , view.frame.size.height);
    }
    view.layer.masksToBounds = TRUE;
    [view.layer addSublayer:sublayer];
    
}

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}

- (IBAction)venueBtnClick:(id)sender {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    VenueDetailVC *myVC = (VenueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"VenueDetailVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];
}
- (void)onVenueViewPressed:(UITapGestureRecognizer *)tapGesture {
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    VenueDetailVC *myVC = (VenueDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"VenueDetailVC"];
    myVC.matchDetails = matchDetails;
    [self.navigationController pushViewController:myVC animated:YES];
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
