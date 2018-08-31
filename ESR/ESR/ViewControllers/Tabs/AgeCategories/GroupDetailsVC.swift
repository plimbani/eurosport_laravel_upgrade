//
//  GroupDetailsVC.swift
//  ESR
//
//  Created by Pratik Patel on 31/08/18.
//

import UIKit

class GroupDetailsVC: SuperViewController {
    
    @IBOutlet var table: UITableView!
    var groupName = NULL_STRING
    
    override func viewDidLoad() {
        super.viewDidLoad()

        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = groupName
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        let value = UIInterfaceOrientation.landscapeLeft.rawValue
        UIDevice.current.setValue(value, forKey: "orientation")
    }

    override var supportedInterfaceOrientations: UIInterfaceOrientationMask {
        return .landscapeLeft
    }
    
    override var shouldAutorotate: Bool {
        return true
    }
}

extension GroupDetailsVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}


