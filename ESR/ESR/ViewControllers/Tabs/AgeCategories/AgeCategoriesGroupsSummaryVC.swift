//
//  AgeCategoriesGroupsSummaryVC.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

class AgeCategoriesGroupsSummaryVC: SuperViewController {

    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_age_categories_groups")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
    }
}

extension AgeCategoriesGroupsSummaryVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
