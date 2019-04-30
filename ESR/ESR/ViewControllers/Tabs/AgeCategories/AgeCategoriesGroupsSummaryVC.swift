//
//  AgeCategoriesGroupsSummaryVC.swift
//  ESR
//
//  Created by Pratik Patel on 28/08/18.
//

import UIKit

class AgeCategoriesGroupsSummaryVC: SuperViewController {

    @IBOutlet var table: UITableView!
    @IBOutlet var tabStandingsSeparator: UIView!
    @IBOutlet var tabMatchesSeparator: UIView!
    @IBOutlet var headerGroupStandingView: UIView!
    @IBOutlet var headerGroupMatchesView: UIView!
    @IBOutlet var footerGroupStandingView: UIView!
    @IBOutlet var tabStandingView: UIView!
    @IBOutlet var tabMatchView: UIView!
    @IBOutlet var groupSelectionView: UIView!
    @IBOutlet var lblGroupName: UILabel!
    @IBOutlet var lblHeaderGroupName: UILabel!
    
    var heightGroupSummaryStandingsCell: CGFloat = 0
    var heightGroupSummaryMatchesCell: CGFloat = 0
    var groupId: Int = NULL_ID
    
    var dicGroup: NSDictionary!
    
    var teamFixuteuresList = [TeamFixture]()
    var groupStandingsList = [GroupStanding]()
    
    var titleList = [String]()
    
    var selectedTab = 0
    var rotateToPortrait = false
    
    var selectedPickerPosition = -1
    
    @IBOutlet var stackViewTab: UIStackView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_age_categories_groups_summary")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        self.tabStandingView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabStandingViewPressed)))
        self.tabMatchView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabMatchViewPressed)))
        self.groupSelectionView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onGroupViewPressed)))
        self.footerGroupStandingView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onFooterGroupStandingView)))
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryStandingsCell)
        heightGroupSummaryStandingsCell = (cellOwner.cell as! GroupSummaryStandingsCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryMatchesCell)
        heightGroupSummaryMatchesCell = (cellOwner.cell as! GroupSummaryMatchesCell).getCellHeight()
        
        for group in ApplicationData.groupsList {
            if let name = (group as! NSDictionary).value(forKey: "display_name") as? String {
                titleList.append(name)
            }
        }
        
        if let name = dicGroup.value(forKey: "display_name") as? String {
            lblGroupName.text = name
        }
        
        if headerGroupStandingView != nil {
            headerGroupStandingView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 60)
        }
        
        if headerGroupMatchesView != nil {
            headerGroupMatchesView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 40)
        }
        
        if footerGroupStandingView != nil {
            footerGroupStandingView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 50)
        }
        
        refreshListByGroup(false)
    }
    
    override func viewWillAppear(_ animated: Bool) {
        if rotateToPortrait {
            APPDELEGATE.deviceOrientation = .portrait
            let valueOrientation = UIInterfaceOrientation.portrait.rawValue
            UIDevice.current.setValue(valueOrientation, forKey: "orientation")
            UIViewController.attemptRotationToDeviceOrientation()
            self.tabBarController?.tabBar.isHidden = false
            rotateToPortrait = false
            
            if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                mainTabViewController.hideTabbar(flag: false)
            }
        }
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
    
    @objc func onTabStandingViewPressed(sender : UITapGestureRecognizer) {
        tabStandingsSeparator.backgroundColor = UIColor.AppColor()
        tabMatchesSeparator.backgroundColor = UIColor.clear
        selectedTab = 0
        
        if groupStandingsList.count == 0 {
            sendGetGroupStadingsRequest()
        } else {
            table.reloadData()
        }
    }
    
    @objc func onTabMatchViewPressed(sender : UITapGestureRecognizer) {
        tabMatchesSeparator.backgroundColor = UIColor.AppColor()
        tabStandingsSeparator.backgroundColor = UIColor.clear
        selectedTab = 1
        
        if teamFixuteuresList.count == 0 {
            sendGetFixturesRequest()
        } else {
            table.reloadData()
        }
    }
    
    @objc func onGroupViewPressed(sender : UITapGestureRecognizer) {
        showPickerVC(selectedPosition: selectedPickerPosition, titleList: titleList, delegate: self)
    }
    
    @objc func onFooterGroupStandingView(sender : UITapGestureRecognizer) {
        if let mainTabViewController = self.parent!.parent as? MainTabViewController {
            mainTabViewController.hideTabbar()
        }
        
        let viewController = Storyboards.AgeCategories.instantiateGroupDetailsVC()
        viewController.groupStandingsList = self.groupStandingsList
        viewController.groupName = lblGroupName.text!
        self.navigationController?.pushViewController(viewController, animated: true)
    }
    
    func sendGetFixturesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }

        parameters["competitionId"] = groupId
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        ApiManager().getMatchFixtures(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    
                    self.teamFixuteuresList.removeAll()
                    
                    for i in 0..<data.count {
                        self.teamFixuteuresList.append(ParseManager.parseTeamFixture(data[i] as! NSDictionary))
                    }
                    
                    if let type = self.dicGroup.value(forKey: "competation_type") as? String {
                        if type != "Elimination" {
                            // Sort array by start date
                            self.teamFixuteuresList.sort(by: { (t1, t2) -> Bool in
                                
                                if let date1 = t1.matchDatetimeObj {
                                    if let date2 = t2.matchDatetimeObj {
                                        return (date1.timeIntervalSinceNow < date2.timeIntervalSinceNow)
                                    } else {
                                        return true
                                    }
                                } else {
                                    return true
                                }
                            })
                        }
                    }
                }
                
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count > 0 {
                    if let status_code = result.value(forKey: "status_code") as? Int {
                        if status_code == 500 {
                            if let msg = result.value(forKey: "tournament_expired") as? String {
                                self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: msg, requestCode: AlertRequestCode.tournamentExpire.rawValue, delegate: self)
                            }
                        }
                    }
                }
            }
        })
    }
    
    func sendGetGroupStadingsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }
        
        parameters["competitionId"] = groupId
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        lblHeaderGroupName.text = lblGroupName.text! + "\n" + String.localize(key: "string_league_table")
        
        ApiManager().getGroupStandings(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                self.groupStandingsList.removeAll()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    for i in 0..<data.count {
                        self.groupStandingsList.append(ParseManager.parseGroupStandings(data[i] as! NSDictionary))
                    }
                }
                
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func refreshListByGroup(_ flag: Bool) {
        
        var groupDic = NSDictionary()
        
        if flag {
            groupDic = ApplicationData.groupsList[selectedPickerPosition] as! NSDictionary
        } else {
            groupDic = dicGroup
        }
        
        groupId = groupDic.value(forKey: "id") as! Int
        
        if let type = groupDic.value(forKey: "competation_type") as? String {
            if type == "Elimination" {
                tabStandingsSeparator.backgroundColor = .clear
                tabStandingView.backgroundColor = .gray
                tabStandingView.isUserInteractionEnabled = false
                tabMatchesSeparator.backgroundColor = .AppColor()
                
                self.view.layoutIfNeeded()
                
                // Selects tab 1
                selectedTab = 1
                sendGetFixturesRequest()
            } else {
                tabStandingView.backgroundColor = .white
                tabStandingView.isUserInteractionEnabled = true
                
                if selectedTab == 0 {
                    tabStandingsSeparator.backgroundColor = .AppColor()
                    tabMatchesSeparator.backgroundColor = .clear
                }
                
                sendGetFixturesRequest()
                sendGetGroupStadingsRequest()
            }
        }
    }
}

extension AgeCategoriesGroupsSummaryVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension AgeCategoriesGroupsSummaryVC: PickerVCDelegate {
    func pickerVCDoneBtnPressed(title: String, lastPosition: Int) {
        selectedPickerPosition = lastPosition
        lblGroupName.text = title
        refreshListByGroup(true)
    }
    
    func pickerVCCancelBtnPressed() {}
}

extension AgeCategoriesGroupsSummaryVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension AgeCategoriesGroupsSummaryVC : UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if selectedTab == 0 {
            return groupStandingsList.count
        }
        
        return teamFixuteuresList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        if selectedTab == 0 {
            return heightGroupSummaryStandingsCell
        }
        
        return heightGroupSummaryMatchesCell
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        if selectedTab == 0 {
            return 60
        } else {
            return 40
        }
    }
    
    func tableView(_ tableView: UITableView, heightForFooterInSection section: Int) -> CGFloat {
        if selectedTab == 0 {
            return 50
        } else {
            return 0
        }
    }
    
    func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        if selectedTab == 0 {
           return headerGroupStandingView
        } else {
           return headerGroupMatchesView
        }
    }
    
    func tableView(_ tableView: UITableView, viewForFooterInSection section: Int) -> UIView? {
        if selectedTab == 0 {
            return footerGroupStandingView
        } else {
            return UIView()
        }
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if selectedTab == 0 {
            var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryStandingsCell") as? GroupSummaryStandingsCell
            if cell == nil {
                _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryStandingsCell")
                cell = cellOwner.cell as? GroupSummaryStandingsCell
            }
            cell?.record = groupStandingsList[indexPath.row]
            cell?.reloadCell()
            return cell!
        } else {
            var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryMatchesCell") as? GroupSummaryMatchesCell
            if cell == nil {
                _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryMatchesCell")
                cell = cellOwner.cell as? GroupSummaryMatchesCell
            }
            cell?.record = teamFixuteuresList[indexPath.row]
            cell?.reloadCell()
            return cell!
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if selectedTab == 1 {
            let viewController = Storyboards.AgeCategories.instantiateMatchInfoVC()
            viewController.dicTeamFixture = self.teamFixuteuresList[indexPath.row]
            self.navigationController?.pushViewController(viewController, animated: true)
        }
    }
}
