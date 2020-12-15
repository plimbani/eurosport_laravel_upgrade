//
//  ViewScheduleImageVC.swift
//  ESR
//
//  Created by Pratik Patel on 26/02/19.
//

import UIKit
import SDWebImage

class ViewScheduleImageVC: UIViewController {

    @IBOutlet var imgView: UIImageView!
    @IBOutlet var btnClose: UIButton!
    @IBOutlet var scrollView: UIScrollView!
    
    var base64String = NULL_STRING
    
    var isFromTeamVC = false
    var isFromTabAgeCategoriesVC = false
    var isFromTabTeamsVC = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        scrollView.delegate = self
        scrollView.minimumZoomScale = 1.0
        scrollView.maximumZoomScale = 10.0
        
        if !base64String.isEmpty {
            let arrayString = base64String.split(separator: ",")
            
            if arrayString.count > 0 {
                if let data = Data(base64Encoded: String(arrayString.last!)) {
                    imgView.image = UIImage(data: data)
                }
            }
        }
        
        if let mainTabViewController = self.parent!.parent as? MainTabViewController {
            mainTabViewController.hideTabbar(flag: true)
        }
        
        let image = UIImage(named: "icon_close")?.withRenderingMode(.alwaysTemplate)
        btnClose.setImage(image, for: .normal)
        btnClose.tintColor = UIColor.AppColor()
        
        APPDELEGATE.deviceOrientation = .landscapeLeft
        let valueOrientation = UIInterfaceOrientation.landscapeLeft.rawValue
        UIDevice.current.setValue(valueOrientation, forKey: "orientation")
        UIViewController.attemptRotationToDeviceOrientation()
    }
    
    @IBAction func btnClosePressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " btnClosePressed")
        for viewController in self.navigationController!.viewControllers {
            if viewController is TeamVC && isFromTeamVC {
                (viewController as! TeamVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            } else if viewController is TabAgeCategoriesVC && isFromTabAgeCategoriesVC {
                (viewController as! TabAgeCategoriesVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            }  else if viewController is TabTeamsVC && isFromTabTeamsVC {
                (viewController as! TabTeamsVC).selectedIndexForRotation = (viewController as! TabTeamsVC).selectedIndex
                (viewController as! TabTeamsVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            }
        }
    }
}

extension ViewScheduleImageVC: UIScrollViewDelegate {
    func viewForZooming(in scrollView: UIScrollView) -> UIView? {
        return imgView
    }
}
