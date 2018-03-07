//
//  TournamentListVC.m
//  ESR
//
//  Created by Aecor Digital on 19/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "TournamentListVC.h"
#import "TournamentListCellNew.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"

@interface TournamentListVC ()

@end

@implementation TournamentListVC
@synthesize defaultTournamentDir;
-(void)GetTournamentLst{
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
        [SVProgressHUD show];
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,Tournaments];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60.0];
        [request setHTTPMethod:@"GET"];
        [request setValue:@"application/json" forHTTPHeaderField:@"Accept"];
        [request setValue:@"application/json; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
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
//                                                  NSSortDescriptor *dateSortDescriptor = [NSSortDescriptor sortDescriptorWithKey:@"date" ascending:YES];
//                                                  arrayUsedForTableView = [arrayUsedForTableView sortedArrayUsingDescriptors:@[dateSortDescriptor]];
                                                  
                                                  //NSMutableArray *sort = [[NSMutableArray alloc] init];
                                                  _tournamentlistArray =[responseDictionary[@"data"] mutableCopy];
                                                  for (int i = 0; i<_tournamentlistArray.count; i++) {
                                                      NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
                                                      [dateformat setDateFormat:@"dd-MM-yyyy"];
                                                      //NSLog(@"%@",[app.defaultTournamentDir valueForKey:@"start_date"]);
                                                      NSDate *startDate = [dateformat dateFromString:[NSString stringWithFormat:@"%@",[[_tournamentlistArray objectAtIndex:i] valueForKey:@"start_date"]]];
                                                      NSDictionary *dir =[[_tournamentlistArray objectAtIndex:i] mutableCopy];
                                                      [dir setValue:startDate forKey:@"start_date"];
                                                      [_tournamentlistArray replaceObjectAtIndex:i withObject:dir];
                                                      //[sort addObject:startDate];
                                                  }
                                                  NSSortDescriptor* sortByDate = [NSSortDescriptor sortDescriptorWithKey:@"start_date" ascending:NO];
                                                  [_tournamentlistArray sortUsingDescriptors:[NSArray arrayWithObject:sortByDate]];
                                                  for (int i=0; i<_tournamentlistArray.count; i++) {
                                                      NSDateFormatter *dateformat = [[NSDateFormatter alloc] init];
                                                      [dateformat setDateFormat:@"dd-MM-YYYY"];
                                                      NSString *startDate = [dateformat stringFromDate:[[_tournamentlistArray objectAtIndex:i] valueForKey:@"start_date"]];
                                                      NSDictionary *dir =[[_tournamentlistArray objectAtIndex:i] mutableCopy];
                                                      [dir setValue:startDate forKey:@"start_date"];
                                                      [_tournamentlistArray replaceObjectAtIndex:i withObject:dir];
                                                      
                                                  }
                                                  _searchListArray =[responseDictionary[@"data"] mutableCopy];
                                                   [self GetFavouriteTournament];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.tableView reloadData];
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        self.offlineView.hidden = false;
        
    }
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
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  defaultTournamentDir =[responseDictionary[@"data"] mutableCopy];
                                                  AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
                                                  app.defaultTournamentDir =[responseDictionary[@"data"] mutableCopy];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      if (defaultFlag == 1) {
                                                          self.alertTitle.text = NSLocalizedString(@"Success", @"");
                                                          self.alertSubTitle.text =NSLocalizedString(@"Default tournament updated successfully", @"");
                                                          self.alertView.hidden = FALSE;
                                                      }
                                                      [self.tableView reloadData];
                                                  });
                                                  //NSLog(@"%@",defaultTournamentDir);
                                                  
                                                  //                                                  selectedTournament = [[responseDictionary valueForKey:@"tournament_id"] integerValue];
                                                  //                                                  tournamentName = [responseDictionary valueForKey:@"name"];
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}


-(void)GetFavouriteTournament{
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
                                                  [SVProgressHUD dismiss];
                                              } else{
                                                  [SVProgressHUD dismiss];
                                                  NSError *parseError = nil;
                                                  NSDictionary *responseDictionary = [NSJSONSerialization JSONObjectWithData:data options:0 error:&parseError];
                                                  _selectedArray =[responseDictionary[@"data"] mutableCopy];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      if (defaultFavouriteFlag == 1) {
                                                          [self setDefaultToutnamet];
                                                      }else{
                                                          [self GetDefaultTournament];
                                                      }
                                                      
                                                      
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
- (void)viewDidLoad {
    [super viewDidLoad];
    defaultFlag =0;
    defaultFavouriteFlag = 0;
    
    // Do any additional setup after loading the view.
}
-(void)viewWillAppear:(BOOL)animated{
    [self GetTournamentLst];
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
     [[UIDevice currentDevice] setValue:@(UIInterfaceOrientationPortrait) forKey:@"orientation"];
}
- (void)reachabilityChanged:(NSNotification*)notification
{
    Reachability* reachability = notification.object;
    if(reachability.currentReachabilityStatus == NotReachable)
        self.offlineView.hidden = false;
    else
        self.offlineView.hidden = TRUE;
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
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return 70 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //NSArray *option=[[screen objectForKey:@"options"] objectForKey:@"optionList"];
    return _tournamentlistArray.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    TournamentListCellNew *cell = (TournamentListCellNew*)[tableView dequeueReusableCellWithIdentifier:@"TournamentListCellNew"];
    cell.nameLbl.text = [[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    cell.dateLbl.text = [NSString stringWithFormat:@"%@ - %@",[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"start_date"],[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"end_date"]];
//    UITapGestureRecognizer *pgr = [[UITapGestureRecognizer alloc]
//                                   initWithTarget:self
//                                   action:@selector(handleTap:)];
//    
//    [cell.faverateBtn addGestureRecognizer:pgr];
    //cell.faverateBtn.tag = indexPath.row;
    cell.faverateBtn.tag = [[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"id"] integerValue];
    [cell.faverateBtn addTarget:self action:@selector(handleButtonClick:) forControlEvents:UIControlEventTouchUpInside];
    cell.defaultBtn.tag = indexPath.row;
    [cell.defaultBtn addTarget:self action:@selector(handleDefaultButtonClick:) forControlEvents:UIControlEventTouchUpInside];
    if (![[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"tournamentLogo"] isKindOfClass:[NSNull class]]) {
        NSURL *url = [NSURL URLWithString:[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"tournamentLogo"]];
        NSURLRequest* request = [NSURLRequest requestWithURL:url];
        [NSURLConnection sendAsynchronousRequest:request
                                           queue:[NSOperationQueue mainQueue]
                               completionHandler:^(NSURLResponse * response,
                                                   NSData * data,
                                                   NSError * error) {
                                   if (!error){
                                       UIImage *image = [UIImage imageWithData:data];
                                       if (image != NULL) {
                                           cell.image.image = image;
                                       }else{
                                           cell.image.image = [UIImage imageNamed:@"globe.png"];
                                       }
                                   }else{
                                   }
                               }];
    }
    
    for (int i=0; i<_selectedArray.count; i++) {
        if ([[_selectedArray objectAtIndex:i] valueForKey:@"tournament_id"]  ==  [[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"id"]) {
            cell.faverateBtn.selected = YES;
            break;
        }else{
            cell.faverateBtn.selected = NO;
        }
    }
    for (int i=0; i<_tournamentlistArray.count; i++) {
        if ([defaultTournamentDir valueForKey:@"tournament_id"]  ==  [[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"id"]) {
            cell.defaultBtn.selected = YES;
            break;
        }else{
            cell.defaultBtn.selected = NO;
        }
    }
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    return cell;
}
-(void)removeFavourite:(NSString *)tournamentID{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id":tournamentID };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,RemoveTournamentFavourite];
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
                                                  //NSLog(@"%@",responseDictionary);
                                                  NSString *message = [responseDictionary valueForKey:@"message"];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
//                                                      self.alertTitle.text = @"Euro-Sporing";
//                                                      self.alertSubTitle.text = message;
//                                                      self.alertView.hidden = FALSE;
                                                  });
                                              
//                                                  UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"ESR" message:message preferredStyle:UIAlertControllerStyleAlert];
//                                                  UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//                                                      
//                                                      [self.navigationController popViewControllerAnimated:TRUE];                                                          }];
//                                                  [alertController addAction:ok];
//                                                  [self presentViewController:alertController animated:YES completion:nil];
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }

}
-(void)addFavourite:(NSString *)tournamentID{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [defaults objectForKey:@"userData"];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id":tournamentID };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,MakeTournamentFavourite];
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
                                                  //NSLog(@"%@",responseDictionary);
                                                  NSString *message = [responseDictionary valueForKey:@"message"];
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self GetFavouriteTournament];
//                                                      UIAlertController *alertController = [UIAlertController alertControllerWithTitle:@"ESR" message:message preferredStyle:UIAlertControllerStyleAlert];
//                                                      UIAlertAction* ok = [UIAlertAction actionWithTitle:@"OK" style:UIAlertActionStyleDefault handler:^(UIAlertAction * action) {
//                                                          
//                                                          [self.navigationController popViewControllerAnimated:TRUE];                                                          }];
//                                                      [alertController addAction:ok];
//                                                      [self presentViewController:alertController animated:YES completion:nil];
                                                      dispatch_async(dispatch_get_main_queue(), ^{
//                                                          self.alertTitle.text = @"Euro-Sporing";
//                                                          self.alertSubTitle.text = message;
//                                                          self.alertView.hidden = FALSE;
                                                      });
                                                  
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(void)setDefaultToutnamet{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *userData = [[defaults objectForKey:@"userData"] mutableCopy];
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        NSDictionary *params = @{@"user_id":[userData valueForKey:@"user_id"],@"tournament_id": [defaultTournamentDir valueForKey:@"id"]};
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        [userData setValue:[defaultTournamentDir valueForKey:@"id"] forKey:@"tournament_id"];
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
                                                  dispatch_async(dispatch_get_main_queue(), ^{
                                                      [self.tableView reloadData];
                                                      [self GetDefaultTournament];
                                                  });
                                                  
                                              }
                                          }];
        [dataTask resume];
    }else{
        
    }
}
-(IBAction)handleDefaultButtonClick:(UIButton *)sender{
    defaultFlag = 1;
    defaultTournamentDir =[_tournamentlistArray objectAtIndex:sender.tag];
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.defaultTournamentDir = [_tournamentlistArray objectAtIndex:sender.tag];
    int flag =0;
    for (int i=0; i<_selectedArray.count;i++) {
        if ([[defaultTournamentDir valueForKey:@"id"] integerValue] ==[[[_selectedArray objectAtIndex:i] valueForKey:@"tournament_id"] integerValue]) {
            flag =1;
            break;
        }
    }
    if (flag == 0) {
        defaultFavouriteFlag = 1;
        [self addFavourite:[defaultTournamentDir valueForKey:@"id"]];
        [_selectedArray addObject:defaultTournamentDir];
        
    }else{
        [self.tableView reloadData];
        [self setDefaultToutnamet];
    }
    
}
- (IBAction)handleButtonClick:(UIButton *)sender {
    
    if (sender.selected == YES) {
        if ([[defaultTournamentDir valueForKey:@"tournament_id"] integerValue] == sender.tag) {
            self.alertTitle.text = NSLocalizedString(@"Error", @"");
            self.alertSubTitle.text = NSLocalizedString(@"Default tournament cannot be removed from favourite.", @"");
            self.alertView.hidden = FALSE;
        }else{
            [sender setSelected:NO];
            for (int i=0; i<_selectedArray.count; i++) {
                if ([[[_selectedArray objectAtIndex:i] valueForKey:@"tournament_id"] integerValue] == sender.tag) {
                    [self removeFavourite:[[_selectedArray objectAtIndex:i] valueForKey:@"tournament_id"]];
                    [_selectedArray removeObjectAtIndex:i];
                }
            }
        }
        
    } else {
        [sender setSelected:YES];
        for (int i =0; i<_tournamentlistArray.count; i++) {
            if ([[[_tournamentlistArray objectAtIndex:i] valueForKey:@"id"] integerValue] == sender.tag ) {
                 [_selectedArray addObject:[_tournamentlistArray objectAtIndex:i]];
                [self addFavourite:[[_tournamentlistArray objectAtIndex:i] valueForKey:@"id"]];
            }
        }
        
    }
    [self.tableView reloadData];
//    user_id
//    tournament_id
    
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
//    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
//    TournamentDetailVC *myVC = (TournamentDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"TournamentDetailVC"];
//    [self.navigationController pushViewController:myVC animated:YES];
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/
#pragma mark - SearchBar Delegate Methods

-(void)searchBar:(UISearchBar *)searchBar textDidChange:(NSString *)searchText
{
    @try
    {
        [_tournamentlistArray removeAllObjects];
        //stringSearch = @"YES";
        NSString *name = @"";
        if ([searchText length] > 0)
        {
            for (int i = 0; i < [_searchListArray count] ; i++)
            {
                name = [[_searchListArray objectAtIndex:i] valueForKey:@"name"];
                //name = [_searchListArray objectAtIndex:i];
                if (name.length >= searchText.length)
                {
                    NSRange titleResultsRange = [name rangeOfString:searchText options:NSCaseInsensitiveSearch];
                    if (titleResultsRange.length > 0)
                    {
                        [_tournamentlistArray addObject:[_searchListArray objectAtIndex:i]];
                    }
                }
            }
        }
        else
        {
            [_tournamentlistArray addObjectsFromArray:_searchListArray];
        }
        [self.tableView reloadData];
    }
    @catch (NSException *exception) {
    }
    
//    NSPredicate *predicate = [NSPredicate predicateWithFormat:@"self contains[c] %@",searchText];
//    // NSPredicate *predicate = [NSPredicate predicateWithFormat:@"ANY SELF = %@", passcode];
//    _tournamentlistArray = [_searchListArray filteredArrayUsingPredicate:predicate];
//    [self.tableView reloadData];
}

- (void)searchBarTextDidBeginEditing:(UISearchBar *)SearchBar
{
    SearchBar.showsCancelButton=YES;
}
- (void)searchBarTextDidEndEditing:(UISearchBar *)theSearchBar
{
    [theSearchBar resignFirstResponder];
}

- (void)searchBarCancelButtonClicked:(UISearchBar *)SearchBar
{
    @try
    {
        SearchBar.showsCancelButton=NO;
        [SearchBar resignFirstResponder];
        [self.tableView reloadData];
    }
    @catch (NSException *exception) {
    }
}
- (void)searchBarSearchButtonClicked:(UISearchBar *)SearchBar
{
    [SearchBar resignFirstResponder];
}


//- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
//{
//    [super preferredInterfaceOrientationForPresentation];
//    return UIInterfaceOrientationLandscapeLeft;
//}
//- (BOOL)shouldAutorotate
//{
//    [super shouldAutorotate];
//    return NO;
//}
//
//- (UIInterfaceOrientationMask)supportedInterfaceOrientations
//{[super supportedInterfaceOrientations];
//    NSLog(@"viewcontroller %lu",(unsigned long)UIInterfaceOrientationMaskLandscape);
//    return UIInterfaceOrientationMaskLandscape;
//}
//- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
//
//    return UIInterfaceOrientationLandscapeLeft;
//}
- (IBAction)alertViewOkBtnClick:(id)sender {
    self.alertView.hidden = TRUE;
    defaultFlag =0;
    defaultFavouriteFlag = 0;
}
@end
