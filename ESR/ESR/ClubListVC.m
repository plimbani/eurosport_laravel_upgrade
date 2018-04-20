//
//  ClubListVC.m
//  ESR
//
//  Created by Aecor Digital on 15/06/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "ClubListVC.h"
#import "AgeCell.h"
#import <AFNetworking/AFNetworking.h>
#import "ClubTeamListVC.h"
#import "Globals.h"
#import "Utils.h"
#import "SVProgressHUD.h"
#import "AppDelegate.h"
#import "ClubTeamCell.h"

@interface ClubListVC ()

@end

@implementation ClubListVC
-(void)getClubList{
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
        NSString *url=[[NSString alloc]initWithFormat:@"%@%@", BaseURL,GetTournamentClub];
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
                                                  NSSortDescriptor *sort = [NSSortDescriptor sortDescriptorWithKey:@"clubName" ascending:YES selector:@selector(caseInsensitiveCompare:)];
                                                  _clublistArray=[[responseDictionary[@"data"] sortedArrayUsingDescriptors:@[sort]] mutableCopy];
                                                  _searchListArray=[[responseDictionary[@"data"] sortedArrayUsingDescriptors:@[sort]] mutableCopy];
//                                                  _clublistArray =[responseDictionary[@"data"] mutableCopy];
//                                                  _searchListArray =[responseDictionary[@"data"] mutableCopy];
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
    self.tableView.keyboardDismissMode = UIScrollViewKeyboardDismissModeOnDrag;
    UITapGestureRecognizer *gestureRecognizer = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(hideKeyboard:)];
    [self.tableView addGestureRecognizer:gestureRecognizer];
    // Do any additional setup after loading the view.
    //self.title = @"CLUB";
    
}

- (void) hideKeyboard:(UITapGestureRecognizer *)sender {
    
    
    CGPoint location = [sender locationInView:self.tableView];
    NSIndexPath *path = [self.tableView indexPathForRowAtPoint:location];
    
    if(path)
    {
        UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
        ClubTeamListVC *myVC = (ClubTeamListVC *)[storyboard instantiateViewControllerWithIdentifier:@"ClubTeamListVC"];
        myVC.clubDir = [_clublistArray  objectAtIndex:path.row];
        [self.navigationController pushViewController:myVC animated:YES];
    }
    else
    {
        // handle tap on empty space below existing rows however you want
        [_searchBar resignFirstResponder];
    }
}
-(void)viewWillAppear:(BOOL)animated{
    [self getClubList];
    self.searchBar.placeholder = NSLocalizedString(@"Search clubs", @"");
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return 50 ;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //NSArray *option=[[screen objectForKey:@"options"] objectForKey:@"optionList"];
    return _clublistArray.count;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
//    AgeCell *cell = (AgeCell*)[tableView dequeueReusableCellWithIdentifier:@"AgeCell"];
//    cell.lbl.text = [NSString stringWithFormat:@"%@ (%@)",[[_clublistArray objectAtIndex:indexPath.row] valueForKey:@"clubName"],[[_clublistArray objectAtIndex:indexPath.row] valueForKey:@"CountryName"]];
    ClubTeamCell *cell = (ClubTeamCell*)[tableView dequeueReusableCellWithIdentifier:@"ClubTeamCell"];
    cell.lbl.text = [NSString stringWithFormat:@"%@",[[_clublistArray objectAtIndex:indexPath.row] valueForKey:@"clubName"]];
    NSURL *url = [NSURL URLWithString:[[_clublistArray objectAtIndex:indexPath.row] valueForKey:@"CountryLogo"]];
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
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    UIStoryboard *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    ClubTeamListVC *myVC = (ClubTeamListVC *)[storyboard instantiateViewControllerWithIdentifier:@"ClubTeamListVC"];
    myVC.clubDir = [_clublistArray  objectAtIndex:indexPath.row];
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
#pragma mark - SearchBar Delegate Methods

-(void)searchBar:(UISearchBar *)searchBar textDidChange:(NSString *)searchText
{
    @try
    {
        [_clublistArray removeAllObjects];
        //stringSearch = @"YES";
        NSString *name = @"";
        if ([searchText length] > 0)
        {
            for (int i = 0; i < [_searchListArray count] ; i++)
            {
                name = [[_searchListArray objectAtIndex:i] valueForKey:@"clubName"];
                //name = [_searchListArray objectAtIndex:i];
                if (name.length >= searchText.length)
                {
                    NSRange titleResultsRange = [name rangeOfString:searchText options:NSCaseInsensitiveSearch];
                    if (titleResultsRange.length > 0)
                    {
                        [_clublistArray addObject:[_searchListArray objectAtIndex:i]];
                    }
                }
            }
        }
        else
        {
            [_clublistArray addObjectsFromArray:_searchListArray];
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
    SearchBar.showsCancelButton=NO;
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
    return UIInterfaceOrientationMaskPortrait;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    
    return UIInterfaceOrientationPortrait;
}
@end
