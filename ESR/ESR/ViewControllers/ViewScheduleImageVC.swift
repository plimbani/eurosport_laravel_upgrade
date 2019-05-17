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
    var imgURL = NULL_STRING
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        scrollView.delegate = self
        scrollView.minimumZoomScale = 1.0
        scrollView.maximumZoomScale = 10.0
        
        imgView.sd_setImage(with: URL(string: imgURL), completed: nil)
        
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
        for viewController in self.navigationController!.viewControllers {
            if viewController is TabAgeCategoriesVC {
                (viewController as! TabAgeCategoriesVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            } else if viewController is TeamVC {
                (viewController as! TeamVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            } else if viewController is CategoryListVC {
                (viewController as! CategoryListVC).rotateToPortrait = true
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
