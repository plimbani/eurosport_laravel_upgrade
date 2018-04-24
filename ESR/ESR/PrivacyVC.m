//
//  PrivacyVC.m
//  ESR
//
//  Created by sanjay on 02/08/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "PrivacyVC.h"
#import "Reachability.h"
#import "Utils.h"

@interface PrivacyVC () {
    NSString *email;
    NSString *phone;
}

@end

@implementation PrivacyVC

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    UITapGestureRecognizer *tapAction = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(lblClick:)];
    tapAction.delegate =self;
    tapAction.numberOfTapsRequired = 1;
    
    //Enable the lable UserIntraction
    _titleLbl.userInteractionEnabled = YES;
    [_titleLbl addGestureRecognizer:tapAction];
    
    [self setTextViewDetail];
}
- (void)lblClick:(UITapGestureRecognizer *)tapGesture {
    [self.navigationController popViewControllerAnimated:TRUE];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
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
    self.titleLbl.text = [NSLocalizedString(@"Privacy & terms", @"") uppercaseString];
    if([Utils isNetworkAvailable] ==YES){
        self.offlineView.hidden = TRUE;
    }else{
        self.offlineView.hidden = false;
    }
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    Reachability* reachability = [Reachability reachabilityForInternetConnection];
    [reachability startNotifier];
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

- (void)viewDidLayoutSubviews {
    [_txtView setContentOffset:CGPointZero animated:NO];
}

- (void)setTextViewDetail {
    NSString *path = [[NSBundle mainBundle] pathForResource:@"PrivacyPolicy" ofType:@"txt"];
    if (path != nil) {
        NSString *content = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
        if ([content length] > 0) {
            [_txtView setSelectable:NO];
            email = @"info@euro-sportring.org";
            phone = @"+31 (0)355489800";
            NSArray *boldStringList = [[NSArray alloc] initWithObjects:@"Privacy and cookie policy at Stichting Euro-Sportring", @"WHICH PERSONAL DATA ARE PROCESSED?", @"WHY EURO-SPORTRING NEEDS DATA", @"HOW LONG EURO-SPORTRING RETAINS DATA", @"SHARING WITH OTHERS", @"ACCESSING, CORRECTING OR REMOVING DATA", @"PROTECTING DATA", nil];
            
            NSMutableAttributedString *atrbString = [[NSMutableAttributedString alloc] initWithString:content];
            NSRange contentRange = NSMakeRange(0, [content length]);
            [atrbString addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:contentRange];
            [atrbString addAttribute:NSFontAttributeName value:[UIFont systemFontOfSize:14.0] range:contentRange];
            [atrbString addAttribute:NSFontAttributeName value:[UIFont systemFontOfSize:16.0] range:[content rangeOfString:@"Euro-Sportring can be contacted as indicated below:"]];
            
            // email and phone
            NSDictionary *attribs = @{NSUnderlineStyleAttributeName: @(NSUnderlineStyleSingle),
                                      NSForegroundColorAttributeName: [UIColor redColor]
                                      };
            [self setAttributedText:attribs inText:atrbString word:email];
            [atrbString addAttributes:attribs range:[content rangeOfString:phone]];
            
            // all Header title bold
            for (NSString *boldString in boldStringList) {
                [atrbString addAttribute:NSFontAttributeName value:[UIFont boldSystemFontOfSize:17.0] range:[content rangeOfString:boldString]];
            }
            
            _txtView.attributedText = atrbString;
            [_txtView addGestureRecognizer:[[UITapGestureRecognizer alloc] initWithTarget:self action: @selector(privacyPolicyPressed:)]];
        }
    }
}

- (void)privacyPolicyPressed:(UITapGestureRecognizer *)sender {
    if ([sender.view isKindOfClass:[UITextView class]]) {
        CGPoint location = [sender locationInView:_txtView];
        UITextPosition *tapPosition = [_txtView closestPositionToPoint:location];
        UITextRange *textRange = [_txtView.tokenizer rangeEnclosingPosition:tapPosition withGranularity:UITextGranularityWord inDirection:UITextLayoutDirectionRight];
        if (textRange != nil) {
            NSString *textClicked = [_txtView textInRange:textRange];
            if ([textClicked length] > 0) {
                if ([email containsString:textClicked]) {
                    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"mailto:%@", email]];
                    if ([[UIApplication sharedApplication] canOpenURL:url]) {
                        [[UIApplication sharedApplication] openURL:url options:@{} completionHandler:^(BOOL success) {
                            //
                        }];
                    }
                } else if ([phone containsString:textClicked]) {
                    phone = [phone stringByReplacingOccurrencesOfString:@"(" withString:@""];
                    phone = [phone stringByReplacingOccurrencesOfString:@")" withString:@""];
                    phone = [phone stringByReplacingOccurrencesOfString:@" " withString:@""];
                    
                    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"tel://%@", phone]];
                    if ([[UIApplication sharedApplication] canOpenURL:url]) {
                        [[UIApplication sharedApplication] openURL:url options:@{} completionHandler:^(BOOL success) {
                            //
                        }];
                    }
                }
            }
        }
    }
}

- (void)setAttributedText:(NSDictionary *)attribs inText:(NSMutableAttributedString *)atrbContentString word:(NSString *)word {
    
    NSUInteger count = 0, length = [atrbContentString length];
    NSRange range = NSMakeRange(0, length);
    
    while (range.location != NSNotFound) {
        range = [[atrbContentString string] rangeOfString:word options:0 range:range];
        if (range.location != NSNotFound) {
            [atrbContentString addAttributes:attribs range:NSMakeRange(range.location, [word length])];
            range = NSMakeRange(range.location + range.length, length - (range.location + range.length));
            count++;
        }
    }
}

@end
