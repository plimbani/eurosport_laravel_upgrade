//
//  TabTeamsViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabTeamsVC: SuperViewController {

    @IBOutlet var contentView: UIView!
    
    @IBOutlet var tabViewList: [UIView]!
    @IBOutlet var tabLineViewList: [UIView]!
    @IBOutlet var tabLabelList: [UILabel]!
    
    var selectedIndex = 0
    var previousIndex = 0
    var viewControllers: [UIViewController]!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    func initialize(){
        titleNavigationBar.lblTitle.text = String.localize(key: "title_teams")
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.hideBackButton()
        
        for i in 0..<tabViewList.count{
            let view = tabViewList[i]
            view.tag = i
            view.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabSelected)))
        }
        
        setupTabs()
    }
    
    func setupTabs() {
        let tabClubsVC = Storyboards.Teams.instantiateClubListVC()
        let tabCategoryVC = Storyboards.Teams.instantiateCategoryListVC()
        let tabGroupVC = Storyboards.Teams.instantiateGroupListVC()
        
        viewControllers = [tabClubsVC, tabCategoryVC, tabGroupVC]
        
        addViewControllerToContentView(false)
    }
    
    func addViewControllerToContentView(_ flag: Bool) {
        
        if flag {
            if selectedIndex == 0 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateClubListVC()
            } else if selectedIndex == 1 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateCategoryListVC()
            } else if selectedIndex == 2 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateGroupListVC()
            }
        }
        
        let vc = viewControllers[selectedIndex]
        
        addChildViewController(vc)
        vc.view.frame = contentView.bounds
        contentView.addSubview(vc.view)
        vc.didMove(toParentViewController: self)
    }
    
    @objc func onTabSelected(sender : UITapGestureRecognizer) {
        TestFairy.log(String(describing: self) + " onTabSelected")
        
        if let viewValue = sender.view {
            if selectedIndex != viewValue.tag {
                selectedIndex = viewValue.tag
                
                tabLabelList[selectedIndex].textColor = .white
                tabLineViewList[selectedIndex].backgroundColor = UIColor.init(named: "teamtabssepcolor")
                
                if selectedIndex != previousIndex {
                    tabLabelList[previousIndex].textColor = UIColor.init(named: "teamtabstextcolor")
                    tabLineViewList[previousIndex].backgroundColor = .clear
                    previousIndex = selectedIndex
                }
                
                // Remove previous view controller
                let previousVC = viewControllers[previousIndex]
                previousVC.willMove(toParentViewController: nil)
                previousVC.view.removeFromSuperview()
                previousVC.removeFromParentViewController()
                
                addViewControllerToContentView(true)
            }
        }
    }
}
