//
//  MapVC.swift
//  ESR
//
//  Created by Pratik Patel on 05/09/18.
//

import UIKit
import GoogleMaps

class MapVC: SuperViewController {

    @IBOutlet var mapView: GMSMapView!
    
    var strLocation: String!
    var location: CLLocationCoordinate2D?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.lblTitle.text = String.localize(key: "title_venue_map")
        
        if strLocation != NULL_STRING && strLocation.contains(",") {
            let splitStrLocation = strLocation.split(separator: ",")
            
            if splitStrLocation.count == 2 {
                location = CLLocationCoordinate2D(latitude: Double(splitStrLocation[0])!, longitude: Double(splitStrLocation[1])!)
                
                if let locationValue = location {
                    let marker = GMSMarker()
                    marker.position = locationValue
                    marker.map = mapView
                }
            }
        }
    }
}

extension MapVC: TitleNavigationBarDelegate{
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
