//
//  TournamentDetailVC.m
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "TournamentDetailVC.h"
#import <AFNetworking/AFNetworking.h>
#import "Globals.h"
#import "Utils.h"
#import <QuartzCore/QuartzCore.h>
#import "AppDelegate.h"
#import "FavTourmanentListVC.h"
#import <MBCircularProgressBar/MBCircularProgressBarView.h>
@interface TournamentDetailVC ()

@end

@implementation TournamentDetailVC

-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    self.tournamentView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.tournamentView.layer.borderWidth = 2.0f;
    
    self.tournamentView.layer.cornerRadius = 15;
    self.tournamentView.layer.masksToBounds = true;
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(tournamentSelect:)
                                                 name:@"tournamentSelect" object:nil];
    self.changeTournamentTxtField.leftView = [self PaddingView];
    self.changeTournamentTxtField.leftViewMode = UITextFieldViewModeAlways;

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
    return UIInterfaceOrientationMaskAllButUpsideDown;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    
    return UIInterfaceOrientationPortrait;
}
-(void) timeLeft: (NSDate *) dateT fromDate:(NSDate *)toDate{
    

    
    NSCalendar *gregorianCalendar = [[NSCalendar alloc] initWithCalendarIdentifier:NSCalendarIdentifierGregorian];
    NSDateComponents *components = [gregorianCalendar components:NSCalendarUnitDay | NSCalendarUnitHour | NSCalendarUnitMinute | NSCalendarUnitSecond
                                                        fromDate:[NSDate date]
                                                          toDate:toDate
                                                         options:0];
    //NSLog(@"day : %ld H: %ld M : %ld S:%ld", [components day], (long)[components hour],(long)[components minute],(long)[components second]);
    if ([components day] >0) {
        self.dayView.value = [components day];
    }else{
        self.dayView.value = 0;
    }
    if ((long)[components minute]>0) {
        self.minuteView.value = (long)[components minute];
    }else{
        self.minuteView.value = 0;
    }
    if ((long)[components hour]>0) {
        self.hourView.value = (long)[components hour];
    }else{
        self.hourView.value = 0;
    }
    if ((long)[components second]>0) {
        self.secondView.value = (long)[components second];
    }else{
        self.secondView.value = 0;
    }
    
}
-(void)countDown:(NSTimer *) aTimer {
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"start_date"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    [self timeLeft:endDate fromDate:startDate];
}
-(void)viewWillDisappear:(BOOL)animated{
    [timer invalidate];
}
-(void)viewWillAppear:(BOOL)animated{
        
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    self.changeTournamentTxtField.text =[app.defaultTournamentDir valueForKey:@"name"];
    self.tournamentName.text = [app.defaultTournamentDir valueForKey:@"name"];
    if (![[app.defaultTournamentDir valueForKey:@"tournamentLogo"] isKindOfClass:[NSNull class]]) {
        NSURL *url = [NSURL URLWithString:[app.defaultTournamentDir valueForKey:@"tournamentLogo"]];
        NSURLRequest* request = [NSURLRequest requestWithURL:url];
        [NSURLConnection sendAsynchronousRequest:request
                                           queue:[NSOperationQueue mainQueue]
                               completionHandler:^(NSURLResponse * response,
                                                   NSData * data,
                                                   NSError * error) {
                                   if (!error){
                                       UIImage *image = [UIImage imageWithData:data];
                                       self.tournamentImage.image = image;                               }
                               }];
    }
    
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"start_date"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    [self timeLeft:endDate fromDate:startDate];
    timer = [NSTimer scheduledTimerWithTimeInterval:1 target:self selector:@selector(countDown:) userInfo:nil repeats:YES];
    NSLog(@"startdate %@ %@",startDate,endDate);
    NSTimeInterval secondsBetween = [endDate timeIntervalSinceDate:startDate];
    //NSLog(@"%f",secondsBetween/60);
    NSDateFormatter *df = [[NSDateFormatter alloc] init];
    
    [df setDateFormat:@"dd"];
    NSString *startDateStr = [df stringFromDate:startDate];
    NSString *endDateStr = [df stringFromDate:endDate];
    //cell.date.text = [df stringFromDate:matchDate];
    [df setDateFormat:@"YYYY"];
    NSString *startYearStr = [df stringFromDate:startDate];
    NSString *endYearStr = [df stringFromDate:endDate];
    [df setDateFormat:@"MMM"];
    if ([[df stringFromDate:startDate] isEqualToString:[df stringFromDate:endDate]]) {
        self.dateLbl.text=[NSString stringWithFormat:@"%@ - %@ %@ %@",startDateStr,endDateStr,[df stringFromDate:startDate],startYearStr];
    }else{
        [NSString stringWithFormat:@"%@ %@ - %@ %@ %@",startDateStr,[df stringFromDate:startDate],startDateStr,[df stringFromDate:endDate],startYearStr];
    }
    
}
- (void)tournamentSelect:(NSNotification *)message {
    self.changeTournamentTxtField.text = [message.object valueForKey:@"Name"];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    self.changeTournamentTxtField.text =[app.defaultTournamentDir valueForKey:@"name"];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"start_date"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    NSLog(@"startdate %@ %@",[app.defaultTournamentDir valueForKey:@"start_date"],[app.defaultTournamentDir valueForKey:@"end_date"]);
    NSLog(@"startdate %@ %@",startDate,endDate);
    NSTimeInterval secondsBetween = [endDate timeIntervalSinceDate:startDate];
    //NSLog(@"%f",secondsBetween/60);
    int remainingTime = (24*60*60) -(secondsBetween/60);
    //NSString *time = [NSString stringWithFormat:@"%@ %@",@"Remaining Time",[self formatTimeFromSeconds:remainingTime]];
//    selectedTournamentIndex =[message.object valueForKey:@"index"];
//    NSLog(@"%@",selectedTournamentIndex);
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (void)viewDidDisappear:(BOOL)animated
{
    [super viewDidDisappear:animated];
    if (![[self.navigationController viewControllers] containsObject: self]) //any other hierarchy compare if it contains self or not
    {
        // the view has been removed from the navigation stack or hierarchy, back is probably the cause
        // this will be slow with a large stack however.
        
        [[NSNotificationCenter defaultCenter] removeObserver:self];
    }
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

- (IBAction)facebookBtnClick:(id)sender {
    UIApplication *application = [UIApplication sharedApplication];
    NSURL *URL = [NSURL URLWithString:@"https://www.facebook.com/eurosportring/"];
    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
        if (success) {
            NSLog(@"Opened url");
        }
    }];
}

- (IBAction)instagramBtnClick:(id)sender {
    UIApplication *application = [UIApplication sharedApplication];
    NSURL *URL = [NSURL URLWithString:@"https://twitter.com/EuroSportring"];
    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
        if (success) {
            NSLog(@"Opened url");
        }
    }];
}

- (IBAction)twitterBtnClick:(id)sender {
    UIApplication *application = [UIApplication sharedApplication];
    NSURL *URL = [NSURL URLWithString:@"http://instagram.com"];
    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
        if (success) {
            NSLog(@"Opened url");
        }
    }];
}

- (IBAction)changeTournamentBnClick:(id)sender {
    FavTourmanentListVC *myVC = (FavTourmanentListVC *)[self.storyboard instantiateViewControllerWithIdentifier:@"FavTourmanentListVC"];
    [self.navigationController pushViewController:myVC animated:YES];
}
@end
