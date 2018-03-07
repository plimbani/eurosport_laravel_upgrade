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
#import "WebVC.h"
#import "SVProgressHUD.h"
#import "Reachability.h"
@interface TournamentDetailVC ()

@end

@implementation TournamentDetailVC

-(UIView *)PaddingView{
    return [[UIView alloc] initWithFrame:CGRectMake(0, 0, 5, 20)];
}
-(void)setFavAndDefault{
    
}
-(void)GetDefaultTournament{
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetTournamentDefault];
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
                                                  AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  app.defaultTournamentDir =[responseDictionary[@"data"] mutableCopy];
//                                                  if (![[app.defaultTournamentDir valueForKey:@"status"] isEqualToString:@"Published"]) {
//                                                      [self setFavAndDefault];
//                                                  }else{
//                                                      [self GetTournamentFavList];
//                                                  }
                                                  [self GetTournamentFavList];
                                                  //NSLog(@"%@",defaultTournamentDir);
                                                  
                                                  //                                                  selectedTournament = [[responseDictionary valueForKey:@"tournament_id"] integerValue];
                                                  //                                                  tournamentName = [responseDictionary valueForKey:@"name"];
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)setDefaultToutnamet:(NSString *)tournament_id{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [[defaults objectForKey:@"userData"] mutableCopy];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id": tournament_id};
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        [userData setValue:tournament_id forKey:@"tournament_id"];
        [[NSUserDefaults standardUserDefaults] setObject:userData forKey:@"userData"];
        [[NSUserDefaults standardUserDefaults] synchronize];
        [userData valueForKey:@"tournament_id"];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,SetTournamentDefault];
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
                                                  //_selectedArray =[responseDictionary[@"data"] mutableCopy];
                                                  NSLog(@"%@",responseDictionary);
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)GetTournamentFavList{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetUserFavouriteTournamentList];
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
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  NSLog(@"%@",responseDictionary);
                                                  NSMutableArray *favTournamentlistArray =[responseDictionary[@"data"] mutableCopy];
                                                  _autoCompleteArray = [responseDictionary[@"data"] mutableCopy];
                                                  if (![[app.defaultTournamentDir valueForKey:@"status"] isEqualToString:@"Published"]) {
                                                      if (favTournamentlistArray >0) {
                                                          app.defaultTournamentDir =[favTournamentlistArray objectAtIndex:0];
                                                          [self setDefaultToutnamet:[app.defaultTournamentDir valueForKey:@"tournament_id"]];
                                                          
                                                          dispatch_async(dispatch_get_main_queue(), ^{
                                                              
                                                              [self updateUI];
                                                              [self.autoCompleteTableView reloadData];
                                                              [self.picker reloadAllComponents];
                                                          });
                                                      }
                                                      
                                                  }else{
                                                      for (int i =0; i<favTournamentlistArray.count; i++) {
                                                          
                                                          if ([[[favTournamentlistArray objectAtIndex:i] valueForKey:@"tournament_id"] integerValue] == [[app.defaultTournamentDir valueForKey:@"tournament_id"] integerValue]) {
                                                              app.defaultTournamentDir =[favTournamentlistArray objectAtIndex:i];
                                                              dispatch_async(dispatch_get_main_queue(), ^{
                                                                  
                                                                  [self updateUI];
                                                                  [self.autoCompleteTableView reloadData];
                                                                  [self.picker reloadAllComponents];
                                                              });
                                                          }
                                                      }
                                                  }
                                                  
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (void)viewDidLoad {
    [super viewDidLoad];
    self.dayLbl.text = NSLocalizedString(@"Days",@"");
    self.hoursLbl.text = NSLocalizedString(@"Hours",@"");
    self.minutesLbl.text = NSLocalizedString(@"Minutes",@"");
    self.secondLbl.text = NSLocalizedString(@"Seconds",@"");
    self.tournamentView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.tournamentView.layer.borderWidth = 2.0f;
    
//    self.tournamentView.layer.cornerRadius = 15;
    self.tournamentView.layer.masksToBounds = true;
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(tournamentSelect:)
                                                 name:@"tournamentSelect" object:nil];
    self.changeTournamentTxtField.leftView = [self PaddingView];
    self.changeTournamentTxtField.leftViewMode = UITextFieldViewModeAlways;
    UIGestureRecognizer *gestureRecognizer = [[UIGestureRecognizer alloc] init];
    gestureRecognizer.delegate = self;
    [self.scrollSubView addGestureRecognizer:gestureRecognizer];
    UITapGestureRecognizer* phone1LblGesture = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(phone1LblTapped)];
    // if labelView is not set userInteractionEnabled, you must do so
    [self.contactLbl setUserInteractionEnabled:YES];
    [self.contactLbl addGestureRecognizer:phone1LblGesture];

}

- (void)phone1LblTapped
{
    UIDevice *device = [UIDevice currentDevice];
    if ([[device model] isEqualToString:@"iPhone"] ) {
        //NSString *phoneNumber = [@"tel://" stringByAppendingString:self.contactLbl.text];
        NSString *phoneNumber = [@"tel://" stringByAppendingString:self.contactLbl.text];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:phoneNumber]];
    } else {
        UIAlertView *Notpermitted=[[UIAlertView alloc] initWithTitle:@"Alert" message:@"Your device doesn't support this feature." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [Notpermitted show];
    }
}
- (BOOL) gestureRecognizer:(UIGestureRecognizer *)gestureRecognizer shouldReceiveTouch:(UITouch *)touch
{
    UIView* view = gestureRecognizer.view;
    CGPoint loc = [gestureRecognizer locationInView:_scrollSubView];
    if([touch.view.superview isKindOfClass:[UITableViewCell class]]) {
        CGPoint loc = [touch locationInView:_autoCompleteTableView];
        NSIndexPath *path = [_autoCompleteTableView indexPathForRowAtPoint:loc];
        self.changeTournamentTxtField.text = [[_autoCompleteArray objectAtIndex:path.row] valueForKey:@"name"];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        app.defaultTournamentDir = [_autoCompleteArray objectAtIndex:path.row];
        NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
        [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
        //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
        NSLog(@"startdate %@ %@",[app.defaultTournamentDir valueForKey:@"TournamentStartTime"],[app.defaultTournamentDir valueForKey:@"end_date"]);
        [self updateUI];
        self.autoCompleteTableView.hidden = TRUE;
        return TRUE;
    }else{
        self.autoCompleteTableView.hidden = TRUE;
        self.pickerView.hidden = TRUE;
        return FALSE;
    }
}
- (NSInteger )numberOfComponentsInPickerView:(UIPickerView *)pickerView
{
    return 1;
}
- (NSAttributedString *)pickerView:(UIPickerView *)pickerView attributedTitleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    NSString *title = [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"]];
    NSAttributedString *attString =
    [[NSAttributedString alloc] initWithString:title attributes:@{NSForegroundColorAttributeName:[UIColor whiteColor]}];
    
    return attString;
}
// The number of rows of data
- (NSInteger )pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component
{
    return _autoCompleteArray.count;
}

// The data to return for the row and component (column) that's being passed in
- (NSString*)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    return [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"]];
    
}
- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    // This method is triggered whenever the user makes a change to the picker selection.
    // The parameter named row and component represents what was selected.
    //self.pickerView.hidden = TRUE;
    self.changeTournamentTxtField.text = [[_autoCompleteArray objectAtIndex:row] valueForKey:@"name"];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.defaultTournamentDir = [_autoCompleteArray objectAtIndex:row];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"TournamentStartTime"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    NSLog(@"startdate %@ %@",[app.defaultTournamentDir valueForKey:@"TournamentStartTime"],[app.defaultTournamentDir valueForKey:@"end_date"]);
    NSLog(@"startdate %@ %@",startDate,endDate);
    NSTimeInterval secondsBetween = [endDate timeIntervalSinceDate:startDate];
    //NSLog(@"%f",secondsBetween/60);
    int remainingTime = (24*60*60) -(secondsBetween/60);
    [self updateUI];
    
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
//    [self.dayView  setUnitFontName:[UIFont fontWithName:@“fontname” size:fontsize]]
//    [objBtn.titleLabel setFont:[UIFont fontWithName:@“fontname” size:fontsize]];
    
}
-(void)countDown:(NSTimer *) aTimer {
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"TournamentStartTime"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    [self timeLeft:endDate fromDate:startDate];
}
-(void)viewWillDisappear:(BOOL)animated{
    [timer invalidate];
}
-(void)updateUI{
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    NSLog(@"%@",app.defaultTournamentDir);
    self.changeTournamentTxtField.text =[app.defaultTournamentDir valueForKey:@"name"];
    self.tournamentName.text = [app.defaultTournamentDir valueForKey:@"name"];
    if (![[app.defaultTournamentDir valueForKey:@"first_name"] isKindOfClass:[NSNull class]] && ![[app.defaultTournamentDir valueForKey:@"last_name"] isKindOfClass:[NSNull class]]) {
        _nameLbl.text = [NSString stringWithFormat:@"%@ %@",[app.defaultTournamentDir valueForKey:@"first_name"],[app.defaultTournamentDir valueForKey:@"last_name"]];
    }else{
        _nameLbl.text = @"";
    }
    if (![[app.defaultTournamentDir valueForKey:@"telephone"] isKindOfClass:[NSNull class]]) {
        self.contactLbl.text = [NSString stringWithFormat:@"%@",[app.defaultTournamentDir valueForKey:@"telephone"]];
    }else{
        self.contactLbl.text = @"";
    }
    //self.contactLbl.text = [app.defaultTournamentDir valueForKey:@"name"];
    NSArray *array = [[app.defaultTournamentDir valueForKey:@"name"] componentsSeparatedByCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    if (array.count ==2) {
        NSLog(@"%@",[NSString stringWithFormat:@"%@\r%@",[array objectAtIndex:0],[array objectAtIndex:1]]);
        self.tournamentName.text=[NSString stringWithFormat:@"%@\n%@",[array objectAtIndex:0],[array objectAtIndex:1]];
    }
    if (array.count ==3) {
        self.tournamentName.text=[NSString stringWithFormat:@"%@ %@\n%@",[array objectAtIndex:0],[array objectAtIndex:1],[array objectAtIndex:2]];
    }
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
                                       if (image != NULL) {
                                           self.tournamentImage.image = image;
                                       }else{
                                           self.tournamentImage.image = [UIImage imageNamed:@"globe.png"];
                                       }
                                       
                                   }else{
                                       NSLog(@"error");
                                   }
                               }];
    }else{
        self.tournamentImage.image = [UIImage imageNamed:@"globe.png"];
    }
    
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"TournamentStartTime"]];
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
    [df setDateFormat:@"MMMM"];
    if ([[df stringFromDate:startDate] isEqualToString:[df stringFromDate:endDate]]) {
        NSLog(@"%@", [startDateStr substringWithRange:NSMakeRange(0, 1)]);
        if ([ [startDateStr substringWithRange:NSMakeRange(0, 1)] isEqualToString:@"0"]) {
            startDateStr = [startDateStr substringWithRange:NSMakeRange(1, 1)];
        }
        if ([ [endDateStr substringWithRange:NSMakeRange(0, 1)] isEqualToString:@"0"]) {
            endDateStr = [endDateStr substringWithRange:NSMakeRange(1, 1)];
        }
//        if (startDateStr.length ==1) {
//            startDateStr = [NSString stringWithFormat:@"%@%@",@"0",startDateStr];
//        }
//        if (endDateStr.length == 1) {
//            endDateStr = [NSString stringWithFormat:@"%@%@",@"0",endDateStr];
//        }
        self.dateLbl.text=[NSString stringWithFormat:@"%@ - %@ %@ %@",startDateStr,endDateStr,NSLocalizedString([df stringFromDate:startDate],@""),startYearStr];
    }else{
       self.dateLbl.text= [NSString stringWithFormat:@"%@ %@ - %@ %@ %@",startDateStr,NSLocalizedString([df stringFromDate:startDate],@""),startDateStr,NSLocalizedString([df stringFromDate:startDate],@""),startYearStr];
    }
}

-(void)viewWillAppear:(BOOL)animated{
    [self.teamsBtn setTitle:NSLocalizedString(@"Teams",@"") forState:UIControlStateNormal];
    self.dayLbl.text = NSLocalizedString(@"Days",@"");
    self.hoursLbl.text = NSLocalizedString(@"Hours",@"");
    self.minutesLbl.text = NSLocalizedString(@"Minutes",@"");
    self.secondLbl.text = NSLocalizedString(@"Seconds",@"");
    [self GetDefaultTournament];
    _autoCompleteTableView.hidden =TRUE;
    // _autoCompleteTableView.tableFooterView = [UIView new];
    [[_autoCompleteTableView layer] setMasksToBounds:NO];
    [[_autoCompleteTableView layer] setShadowColor:[UIColor blackColor].CGColor];
    [[_autoCompleteTableView layer] setShadowOffset:CGSizeMake(0.0f, 5.0f)];
    [[_autoCompleteTableView layer] setShadowOpacity:0.3f];
    self.contactDetailAlertView.hidden = TRUE;
    self.pickerView.hidden = TRUE;
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
    
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
}
-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    NSLog(@"%ld",35*_autoCompleteArray.count);
    self.tableHeight.constant = 35*_autoCompleteArray.count;
    return [_autoCompleteArray count];
}
-(CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(nonnull NSIndexPath *)indexPath
{
    return 35;
}
-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    //    TournamentListCell *cell = (TournamentListCell*)[tableView dequeueReusableCellWithIdentifier:@"TournamentListCell"];
    //    cell.lbl.text = [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    //    return cell;
    UITableViewCell *Cell = [tableView dequeueReusableHeaderFooterViewWithIdentifier:nil];
    if (Cell ==nil) {
        Cell = [[UITableViewCell alloc]initWithStyle:UITableViewCellStyleDefault reuseIdentifier:nil];
    }
    
    Cell.textLabel.text = [NSString stringWithFormat:@"%@", [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"]];
    Cell.contentView.backgroundColor = [UIColor whiteColor];
    Cell.backgroundColor = [UIColor clearColor];
    return Cell;
}

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    UITableViewCell *Cell = [tableView cellForRowAtIndexPath:indexPath];
    self.autoCompleteTableView.hidden = TRUE;
    self.changeTournamentTxtField.text = [[_autoCompleteArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.defaultTournamentDir = [_autoCompleteArray objectAtIndex:indexPath.row];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"TournamentStartTime"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    NSLog(@"startdate %@ %@",[app.defaultTournamentDir valueForKey:@"TournamentStartTime"],[app.defaultTournamentDir valueForKey:@"end_date"]);
    NSLog(@"startdate %@ %@",startDate,endDate);
    NSTimeInterval secondsBetween = [endDate timeIntervalSinceDate:startDate];
    //NSLog(@"%f",secondsBetween/60);
    int remainingTime = (24*60*60) -(secondsBetween/60);
    [self updateUI];
}
- (void)tournamentSelect:(NSNotification *)message {
    self.changeTournamentTxtField.text = [message.object valueForKey:@"Name"];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    self.changeTournamentTxtField.text =[app.defaultTournamentDir valueForKey:@"name"];
    NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
    [dateformat setDateFormat:@"YYYY-MM-dd HH:mm:ss"];
    //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
    NSDate *startDate = [dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"TournamentStartTime"]];
    NSDate *endDate =[dateformat dateFromString:[app.defaultTournamentDir valueForKey:@"end_date"]];
    NSLog(@"startdate %@ %@",[app.defaultTournamentDir valueForKey:@"TournamentStartTime"],[app.defaultTournamentDir valueForKey:@"end_date"]);
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
//    UIApplication *application = [UIApplication sharedApplication];
//    NSURL *URL = [NSURL URLWithString:@"https://www.facebook.com/eurosportring/"];
//    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
//        if (success) {
//            NSLog(@"Opened url");
//        }
//    }];
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    WebVC *myVC = (WebVC *)[storyboard instantiateViewControllerWithIdentifier:@"WebVC"];
    myVC.socialName = @"facebook";
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)instagramBtnClick:(id)sender {
//    UIApplication *application = [UIApplication sharedApplication];
//    NSURL *URL = [NSURL URLWithString:@"http://instagram.com/_u/eurosports"];
//    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
//        if (success) {
//            NSLog(@"Opened url");
//        }
//    }];
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    WebVC *myVC = (WebVC *)[storyboard instantiateViewControllerWithIdentifier:@"WebVC"];
    myVC.socialName = @"instagram";
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)twitterBtnClick:(id)sender {
//    UIApplication *application = [UIApplication sharedApplication];
//    NSURL *URL = [NSURL URLWithString:@"https://twitter.com/EuroSportring"];
//    [application openURL:URL options:@{} completionHandler:^(BOOL success) {
//        if (success) {
//            NSLog(@"Opened url");
//        }
//    }];
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    WebVC *myVC = (WebVC *)[storyboard instantiateViewControllerWithIdentifier:@"WebVC"];
    myVC.socialName = @"twitter";
    [self.navigationController pushViewController:myVC animated:YES];
}

- (IBAction)changeTournamentBnClick:(id)sender {
//    FavTourmanentListVC *myVC = (FavTourmanentListVC *)[self.storyboard instantiateViewControllerWithIdentifier:@"FavTourmanentListVC"];
//    [self.navigationController pushViewController:myVC animated:YES];
    //self.autoCompleteTableView.hidden = FALSE;
    self.pickerView.hidden = FALSE;
}

- (IBAction)pickerOkBtnClick:(id)sender {
    self.pickerView.hidden = TRUE;
}

- (IBAction)pickerCancelBtnClick:(id)sender {
    self.pickerView.hidden = TRUE;
}

- (IBAction)teamBtnClick:(id)sender {
    [[NSNotificationCenter defaultCenter] postNotificationName:@"teamBtnClick" object:nil];
    self.tabBarController.selectedIndex = 2;
}

- (IBAction)finalPlacingBtnClick:(id)sender {
}
- (IBAction)closeBtnClick:(id)sender {
    self.contactDetailAlertView.hidden = TRUE;
}

- (IBAction)contactBtnClick:(id)sender {
    self.contactDetailAlertView.hidden = FALSE;
}
@end
