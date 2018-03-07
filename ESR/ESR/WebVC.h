//
//  WebVC.h
//  ESR
//
//  Created by Aecor Digital on 11/08/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WebVC : UIViewController<UIGestureRecognizerDelegate>
@property (weak, nonatomic) IBOutlet UIWebView *webView;
@property (strong, nonatomic) NSString *socialName;
@property (weak, nonatomic) IBOutlet UILabel *titleLbl;

- (IBAction)backBtnClick:(id)sender;
@end
