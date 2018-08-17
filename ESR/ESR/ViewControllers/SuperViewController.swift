//
//  SuperViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class SuperViewController: UIViewController {

    @IBOutlet var titleNavigationBar: TitleNavigationBar!
    @IBOutlet var imageNavigationBar: ImageNavigationBar!
    
    let cellOwner = TableCellOwner()
    @IBOutlet var heightConstraintLblNoInternet: NSLayoutConstraint!
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    func setConstraintLblNoInternet(_ isShow: Bool) {
        heightConstraintLblNoInternet.constant = isShow ? 20 : 0
    }
}

