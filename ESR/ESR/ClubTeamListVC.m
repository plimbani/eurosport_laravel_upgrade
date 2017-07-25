//
//  ClubTeamListVC.m
//  ESR
//
//  Created by Aecor Digital on 29/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ClubTeamListVC.h"
#import "ClubTeamCell.h"
#import <AFNetworking/AFNetworking.h>
#import "TournamentDetailVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "TeamDetailVC.h"

@interface ClubTeamListVC ()

@end

@implementation ClubTeamListVC
@synthesize clubDir;
-(void)getClubTeamList{
    
    if([Utils isNetworkAvailable] ==YES){
        [SVProgressHUD show];
        AppDelegate *app = (AppDelegate*)[[UIApplication sharedApplication] delegate];
        NSDictionary *params = @{@"tournament_id":[app.defaultTournamentDir valueForKey:@"tournament_id"],@"club_id":[clubDir valueForKey:@"ClubId"] };
        NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
        NSString *token = [defaults objectForKey:@"token"];
        NSString *concateToken = [NSString stringWithFormat:@"%@%@",@"Bearer ",token];
        NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
        NSDictionary *header =@{@"IsMobileUser": @"true",@"Authorization":concateToken};
        sessionConfiguration.HTTPAdditionalHeaders = header;
        NSURLSession *session = [NSURLSession sessionWithConfiguration:sessionConfiguration];
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetClubTeam];
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
                                                  
                                                  _clubTeamArray =[responseDictionary[@"data"] mutableCopy];
                                                  NSLog(@"%@",_clubTeamArray);
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
    [self getClubTeamList];
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
    return 50 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //NSArray *option=[[screen objectForKey:@"options"] objectForKey:@"optionList"];
    return _clubTeamArray.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    ClubTeamCell *cell = (ClubTeamCell*)[tableView dequeueReusableCellWithIdentifier:@"ClubTeamCell"];
    cell.lbl.text = [[_clubTeamArray objectAtIndex:indexPath.row] valueForKey:@"name"];
    //cell.lblRowData.text=[[[[screen objectForKey:@"options"] objectForKey:@"optionList"] objectAtIndex:indexPath.row] objectForKey:@"text"];
    NSURL *url = [NSURL URLWithString:[[_clubTeamArray objectAtIndex:indexPath.row] valueForKey:@"countryLogo"]];
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
    return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        TeamDetailVC *myVC = (TeamDetailVC *)[storyboard instantiateViewControllerWithIdentifier:@"TeamDetailVC"];
        myVC.teamDetails = [_clubTeamArray objectAtIndex:indexPath.row];
        [self.navigationController pushViewController:myVC animated:YES];
}
- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
