//
//  MapVC.h
//  ESR
//
//  Created by Aecor Digital on 07/07/17.
//  Copyright © 2017 Aecor Digital. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>
#import <MapKit/MapKit.h>
@interface MapVC : UIViewController<CLLocationManagerDelegate,MKMapViewDelegate>{
    CLLocationManager *locationManager;
}
@property (weak, nonatomic) IBOutlet MKMapView *mapView;
@property (strong, nonatomic) NSMutableDictionary *matchDetails;
- (IBAction)backBtnClick:(id)sender;
@end
