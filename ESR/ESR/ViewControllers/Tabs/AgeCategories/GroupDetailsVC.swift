//
//  GroupDetailsVC.swift
//  ESR
//
//  Created by Pratik Patel on 31/08/18.
//

import UIKit

class GroupDetailsVC: SuperViewController {
    
    @IBOutlet var table: UITableView!
    @IBOutlet var headerView: UIView!
    @IBOutlet var lblGroupName: UILabel!
    var groupName = NULL_STRING
    var heightGroupDetailCell: CGFloat = 0
    var groupStandingsList = [GroupStanding]()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = groupName
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
         // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        lblGroupName.text = groupName
        
        let value = UIInterfaceOrientation.landscapeLeft.rawValue
        UIDevice.current.setValue(value, forKey: "orientation")
        
        _ = cellOwner.loadMyNibFile(nibName: "GroupDetailsCell")
        heightGroupDetailCell = (cellOwner.cell as! GroupDetailsCell).getCellHeight()
        
        headerView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 60)
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }

    override var supportedInterfaceOrientations: UIInterfaceOrientationMask {
        return .landscapeRight
    }
    
    override var shouldAutorotate: Bool {
        return true
    }
}

extension GroupDetailsVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        for viewController in self.navigationController!.viewControllers {
            if viewController is AgeCategoriesGroupsSummaryVC {
                (viewController as! AgeCategoriesGroupsSummaryVC).rotateToPortrait = true
                self.navigationController?.popToViewController(viewController, animated: true)
                break
            }
        }
    }
}

extension GroupDetailsVC : UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return groupStandingsList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 40
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        return 50
    }
    
    func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        return headerView
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        var cell = tableView.dequeueReusableCell(withIdentifier: "GroupDetailsCell") as? GroupDetailsCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "GroupDetailsCell")
            cell = cellOwner.cell as? GroupDetailsCell
        }
        cell?.record = groupStandingsList[indexPath.row]
        cell?.reloadCell()
        return cell!
    }
}
