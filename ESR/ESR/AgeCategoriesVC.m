//
//  AgeCategoriesVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "AgeCategoriesVC.h"
#import "AgeGroupVC.h"
#import "Utils.h"
#import "Reachability.h"

@interface AgeCategoriesVC ()

@end

@implementation AgeCategoriesVC
-(void)getAgeList{
    
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSDictionary *params = @{@"tournament_id":[app.defaultTournamentDir valueForKey:@"tournament_id"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetTournamentAge];
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
                                                  NSLog(@"%@",responseDictionary);
                                                  _agelistArray =[responseDictionary[@"data"] mutableCopy];
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
    // Do any additional setup after loading the view.
}
-(void)viewWillAppear:(BOOL)animated{
    self.ageCategoriesLbl.text = [NSLocalizedString(@"Age categories", @"") uppercaseString];
    [self getAgeList];
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
- (IBAction)handleButtonClick:(UIButton *)sender {
    
    if (![[[_agelistArray objectAtIndex:sender.tag] valueForKey:@"comments"] isKindOfClass:[NSNull class]] ) {
        self.commentLbl.text = [[_agelistArray objectAtIndex:sender.tag] valueForKey:@"comments"];;
        self.commentView.hidden = FALSE;
    }
    
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return 50 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //NSArray *option=[[screen objectForKey:@"options"] objectForKey:@"optionList"];
    return _agelistArray.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    AgeCell *cell = (AgeCell*)[tableView dequeueReusableCellWithIdentifier:@"AgeCell"];
    cell.lbl.text = [NSString stringWithFormat:@"%@ (%@)",[[_agelistArray objectAtIndex:indexPath.row] valueForKey:@"group_name"],[[_agelistArray objectAtIndex:indexPath.row] valueForKey:@"category_age"]];
    //cell.lblRowData.text=[[[[screen objectForKey:@"options"] objectForKey:@"optionList"] objectAtIndex:indexPath.row] objectForKey:@"text"];
    cell.commentBtnClick.tag = indexPath.row;
    if ([[[_agelistArray objectAtIndex:indexPath.row] valueForKey:@"comments"] isKindOfClass:[NSNull class]] ) {
        cell.commentBtnClick.hidden = TRUE;
    }
    //cell.faverateBtn.tag = [[[_tournamentlistArray objectAtIndex:indexPath.row] valueForKey:@"id"] integerValue];
    [cell.commentBtnClick addTarget:self action:@selector(handleButtonClick:) forControlEvents:UIControlEventTouchUpInside];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
    app.competationFormatId = [[_agelistArray  objectAtIndex:indexPath.row]valueForKey:@"id"];
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    AgeGroupVC *myVC = (AgeGroupVC *)[storyboard instantiateViewControllerWithIdentifier:@"AgeGroupVC"];
    myVC.ageDir = [_agelistArray  objectAtIndex:indexPath.row];
    [self.navigationController pushViewController:myVC animated:YES];
}
- (IBAction)closeBtnClick:(id)sender {
    self.commentView.hidden = TRUE;
}
@end
