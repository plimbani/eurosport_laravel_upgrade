//
//  MapVC.m
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import "MapVC.h"

@interface MapVC ()

@end

@implementation MapVC
@synthesize matchDetails;

- (void)viewDidLoad {
    [super viewDidLoad];
    self.mapView.delegate = self;
    locationManager = [[CLLocationManager alloc] init];
    locationManager.delegate = self;
    [locationManager requestWhenInUseAuthorization];
    locationManager.distanceFilter = kCLDistanceFilterNone;
    locationManager.desiredAccuracy = kCLLocationAccuracyBest;
    if (![[matchDetails valueForKey:@"venueCoordinates"] isKindOfClass:[NSNull class]]) {
        NSArray *cordinatestrings = [[matchDetails valueForKey:@"venueCoordinates"] componentsSeparatedByString:@","];
        // Do any additional setup after loading the view.
        float lat = [[cordinatestrings objectAtIndex:0] floatValue];
        float log = [[cordinatestrings objectAtIndex:1] floatValue];
        CLLocationCoordinate2D coordinate;
        coordinate.latitude = lat;
        coordinate.longitude = log;
        // Add an annotation
        MKPointAnnotation *point = [[MKPointAnnotation alloc] init];
        point.coordinate = coordinate;
        point.title = [matchDetails valueForKey:@"pitch_number"];
        point.subtitle = [matchDetails valueForKey:@"pitchType"];
        dispatch_async(dispatch_get_main_queue(), ^{
            [self.mapView addAnnotation:point];
        });
        CLLocation *location = [locationManager location];
        [locationManager startUpdatingLocation];
        //CLLocationCoordinate2D coordinate = [location coordinate];
        NSLog(@"%f",coordinate.latitude);
        MKCoordinateRegion mapRegion;
        mapRegion.center = coordinate;
        mapRegion.span.latitudeDelta = .7;
        mapRegion.span.longitudeDelta = .7;
        
        CLLocationCoordinate2D noLocation;
        MKCoordinateRegion viewRegion = MKCoordinateRegionMakeWithDistance(coordinate, 500, 500);
        MKCoordinateRegion adjustedRegion = [self.mapView regionThatFits:viewRegion];
        [self.mapView setRegion:adjustedRegion animated:YES];
        
    }
    
    
    
}
- (MKAnnotationView *)mapView:(MKMapView *)mapView viewForAnnotation:(id<MKAnnotation>)annotation
{
    static NSString * const annotationIdentifier = @"CustomAnnotation";
    
    MKAnnotationView *annotationView = [mapView dequeueReusableAnnotationViewWithIdentifier:annotationIdentifier];
    if (annotationView)
    {
        annotationView.annotation = annotation;
    }
    else
    {
        //        annotationView = [[MKAnnotationView alloc] initWithAnnotation:annotation reuseIdentifier:annotationIdentifier];
        //        annotationView.image = [UIImage imageNamed:@"cb_check.png"];
        //        annotationView.alpha = 0.5;
        
    }
    if ([annotation isKindOfClass:[MKUserLocation class]]){
        
        annotationView = [[MKAnnotationView alloc] initWithAnnotation:annotation reuseIdentifier:annotationIdentifier];
        annotationView.image = [UIImage imageNamed:@"map_marker.png"];
        // annotationView.canShowCallout = YES;
        annotationView.alpha = 1.0;
        annotationView.tag = 1;
    }else{
        MKPinAnnotationView *pinView = nil;
        static NSString *defaultPinID = @"com.aecor.pin";
        pinView = (MKPinAnnotationView *)[mapView dequeueReusableAnnotationViewWithIdentifier:defaultPinID];
        if ( pinView == nil ) pinView = [[MKPinAnnotationView alloc]
                                         initWithAnnotation:annotation reuseIdentifier:defaultPinID];
        pinView.tag =2;
        pinView.pinTintColor = [UIColor purpleColor];
        pinView.canShowCallout = YES;
        pinView.animatesDrop = YES;
        return pinView;
        
        //return nil;
    }
    annotationView.canShowCallout = NO;
    
    return annotationView;
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

- (IBAction)backBtnClick:(id)sender {
    [self.navigationController popViewControllerAnimated:TRUE];
}
@end
