//
//  LanguageVC.m
//  ESR
//
//  Created by Aecor Digital on 05/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "LanguageVC.h"

@interface LanguageVC ()

@end

@implementation LanguageVC

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    languageArray = [[NSMutableArray alloc] initWithObjects:@"English",@"French",@"Italian",@"German",@"Dutch",@"Czech",@"Danish",@"Polish", nil];
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
-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return [languageArray count];
}
-(CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(nonnull NSIndexPath *)indexPath
{
    return 35;
}
-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    UITableViewCell *Cell = [tableView dequeueReusableHeaderFooterViewWithIdentifier:nil];
    if (Cell ==nil) {
        Cell = [[UITableViewCell alloc]initWithStyle:UITableViewCellStyleDefault reuseIdentifier:nil];
    }
    
    Cell.textLabel.text = [NSString stringWithFormat:@"%@",[languageArray objectAtIndex:indexPath.row]];
    Cell.contentView.backgroundColor = [UIColor whiteColor];
    Cell.backgroundColor = [UIColor clearColor];
    
    
    return Cell;
}

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    UITableViewCell *Cell = [tableView cellForRowAtIndexPath:indexPath];
    NSDictionary *obj = @{@"index":[NSString stringWithFormat:@"%ld",(long)indexPath.row],@"Name":[languageArray objectAtIndex:indexPath.row]};
    [[NSNotificationCenter defaultCenter] postNotificationName:@"languageSelect" object:obj];
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
