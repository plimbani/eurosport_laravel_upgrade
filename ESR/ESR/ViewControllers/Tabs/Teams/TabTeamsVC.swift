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
        let tabClubsVC = Storyboards.Teams.instantiateClubClubsVC()
        let tabCategoryVC = Storyboards.Teams.instantiateClubCategoryVC()
        let tabGroupVC = Storyboards.Teams.instantiateClubGroupVC()
        
        viewControllers = [tabClubsVC, tabCategoryVC, tabGroupVC]
        
        addViewControllerToContentView(false)
    }
    
    func addViewControllerToContentView(_ flag: Bool) {
        
        if flag {
            if selectedIndex == 0 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateClubClubsVC()
            } else if selectedIndex == 1 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateClubCategoryVC()
            } else if selectedIndex == 2 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateClubGroupVC()
            }
        }
        
        let vc = viewControllers[selectedIndex]
        
        addChildViewController(vc)
        vc.view.frame = contentView.bounds
        contentView.addSubview(vc.view)
        vc.didMove(toParentViewController: self)
    }
    
    @objc func onTabSelected(sender : UITapGestureRecognizer) {
        
        if let viewValue = sender.view {
            if selectedIndex != viewValue.tag {
                selectedIndex = viewValue.tag
                
                tabLabelList[selectedIndex].textColor = .white
                tabLineViewList[selectedIndex].backgroundColor = .teamTabOrange
                
                if selectedIndex != previousIndex {
                    tabLabelList[previousIndex].textColor = .teamTabLblDefault
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
