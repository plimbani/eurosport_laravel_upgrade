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
    var teamFixuteuresDivisionsList = [String]()
    var teamFixuteuresDictionariesList = [[String: [TeamFixture]]]()
    
    var groupStandingsList = [GroupStanding]()
    
    var ageCategoriesGroupsDropDownList = NSMutableArray()
    // var titleList = [String]()
    
    var selectedTab = 0
    var rotateToPortrait = false
    
    var dropDownCellHeight: CGFloat = 40
    
    var isElimination = false
    @IBOutlet var lblTabMatchHeader: UILabel!
    
    @IBOutlet var dropdownTableView: UITableView!
    
    // var selectedPickerPosition = -1
    
    @IBOutlet var stackViewTab: UIStackView!
    
    var isDivisionRow = false
    
    var hideDropDownTableView: Bool = true {
        didSet {
            dropdownTableView.isHidden = hideDropDownTableView
        }
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    override func viewDidLayoutSubviews() {
        var dropDownViewHeight = CGFloat(ageCategoriesGroupsDropDownList.count) * dropDownCellHeight
        
        let remainingHeight: CGFloat =  UIScreen.main.bounds.size.height - (64 + 50 + 44 + 40 + 45)
        
        if dropDownViewHeight > remainingHeight {
            dropDownViewHeight = remainingHeight
        }
        
        var frame = dropdownTableView.frame
        frame.size.height = CGFloat(dropDownViewHeight)
        dropdownTableView.frame = frame
    }
    
    func initialize() {
        dropdownTableView.reloadData()
        hideDropDownTableView = true
        let adjustForTabbarInsets: UIEdgeInsets = UIEdgeInsets.init(top: 0, left: 0, bottom: 60, right: 0)
        table.contentInset = adjustForTabbarInsets
        table.scrollIndicatorInsets = adjustForTabbarInsets
        table.register(UINib(nibName: "MatchHeaderView", bundle: nil), forHeaderFooterViewReuseIdentifier: "MatchHeaderView")
        
        // table.contentInset = UIEdgeInsets.init(top: -30, left: 0, bottom: 0, right: 0)
        
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
        
        refreshListByGroup()
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
        TestFairy.log(String(describing: self) + " onTabStandingViewPressed")
        hideDropDownTableView = true
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
        TestFairy.log(String(describing: self) + " onTabMatchViewPressed")
        hideDropDownTableView = true
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
        TestFairy.log(String(describing: self) + " onGroupViewPressed")
        hideDropDownTableView = !hideDropDownTableView
    }
    
    @objc func onFooterGroupStandingView(sender : UITapGestureRecognizer) {
        TestFairy.log(String(describing: self) + " onFooterGroupStandingView")
        if let mainTabViewController = self.parent!.parent as? MainTabViewController {
            mainTabViewController.hideTabbar()
        }
        
        let viewController = Storyboards.AgeCategories.instantiateGroupDetailsVC()
        viewController.groupStandingsList = self.groupStandingsList
        viewController.groupName = lblGroupName.text!
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}

extension AgeCategoriesGroupsSummaryVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension AgeCategoriesGroupsSummaryVC : TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        hideDropDownTableView = true
        self.navigationController?.popViewController(animated: true)
    }
}

extension AgeCategoriesGroupsSummaryVC : UITableViewDataSource, UITableViewDelegate {
    
    func numberOfSections(in tableView: UITableView) -> Int {
        
        if tableView == dropdownTableView {
            return 1
        }
        
        if isDivisionRow || isElimination {
            return teamFixuteuresDivisionsList.count
        }
        
        return 1
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        if tableView == dropdownTableView {
            return ageCategoriesGroupsDropDownList.count
        } else {
            if selectedTab == 0 {
                return groupStandingsList.count
            }
            if isDivisionRow || isElimination {
                if teamFixuteuresDictionariesList.count > 0 {
                    return teamFixuteuresDictionariesList[section].values.first?.count ?? 0
                }
            }
            return teamFixuteuresList.count
        }
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        
        if tableView == dropdownTableView {
            return dropDownCellHeight
        } else {
            if selectedTab == 0 {
                return heightGroupSummaryStandingsCell
            }
            
            return heightGroupSummaryMatchesCell
        }
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        
        if tableView == dropdownTableView {
            return 0
        } else {
            if selectedTab == 0 {
                return 60
            } else {
                if isDivisionRow || isElimination {
                    return 40
                }
                
                return CGFloat.leastNormalMagnitude
            }
        }
    }
    
    func tableView(_ tableView: UITableView, heightForFooterInSection section: Int) -> CGFloat {
        if tableView == dropdownTableView {
            return 0
        } else {
            if selectedTab == 0 {
                return 50
            } else {
                return 0
            }
        }
    }
    
    func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        if tableView == dropdownTableView {
            return UIView()
        } else {
            if selectedTab == 0 {
                return headerGroupStandingView
            } else {
                if isDivisionRow || isElimination {
                    let headerView = tableView.dequeueReusableHeaderFooterView(withIdentifier: "MatchHeaderView" ) as! MatchHeaderView
                    
                    if let divisionName = self.dicGroup.value(forKey: "divisionName") as? String {
                        headerView.lblTitle.text = divisionName + " - " + teamFixuteuresDivisionsList[section]
                        
                        var competation_round_no = ""
                        
                        if teamFixuteuresDictionariesList.count > 0 {
                            competation_round_no = (teamFixuteuresDictionariesList[section].values.first?[0].competationRoundNo ?? "")
                            headerView.lblTitle.text = divisionName + " - " + teamFixuteuresDivisionsList[section] + " (\(competation_round_no))"
                        }
                    }
                    
                    if isElimination {
                        headerView.lblTitle.text = teamFixuteuresDivisionsList[section]
                    }
                    
                    return headerView
                }
                return UIView()
            }
        }
    }
    
    func tableView(_ tableView: UITableView, viewForFooterInSection section: Int) -> UIView? {
        if tableView == dropdownTableView {
            return UIView()
        } else {
            if selectedTab == 0 {
                return footerGroupStandingView
            } else {
                return UIView()
            }
        }
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if tableView == dropdownTableView {
            var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryDropdownCell") as? GroupSummaryDropdownCell
            if cell == nil {
                _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryDropdownCell")
                cell = cellOwner.cell as? GroupSummaryDropdownCell
            }
            let dic = ageCategoriesGroupsDropDownList[indexPath.row] as! NSDictionary
            
            if dic[ApplicationData.dicKeyDivision] as! Bool {
                if let title = dic.value(forKey: "title") as? String {
                    cell?.lblTitle.text = title
                    cell?.lblTitle.font = UIFont.init(name: Font.HELVETICA_MEDIUM, size: Font.Size.commonLblSize)
                }
            } else {
                if let title = dic.value(forKey: "display_name") as? String {
                    cell?.lblTitle.text = title
                    cell?.lblTitle.font = UIFont.init(name: Font.HELVETICA_REGULAR, size: Font.Size.commonLblSize)
                }
                cell?.leadingConstraintLblTitle.constant = 25
            }
            
            return cell!
        } else {
            if selectedTab == 0 {
                var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryStandingsCell") as? GroupSummaryStandingsCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryStandingsCell")
                    cell = cellOwner.cell as? GroupSummaryStandingsCell
                }
                cell?.record = groupStandingsList[indexPath.row]
                cell?.isFromAgecategory = true
                cell?.reloadCell()
                return cell!
            } else {
                var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryMatchesCell") as? GroupSummaryMatchesCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryMatchesCell")
                    cell = cellOwner.cell as? GroupSummaryMatchesCell
                }
                
                if isDivisionRow || isElimination {
                    if let arrayOfDivision = teamFixuteuresDictionariesList[indexPath.section].values.first {
                        cell?.record = arrayOfDivision[indexPath.row]
                    }
                } else {
                    cell?.record = teamFixuteuresList[indexPath.row]
                }
                
                cell?.reloadCell()
                return cell!
            }
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        
        if tableView == dropdownTableView {
            dicGroup = ageCategoriesGroupsDropDownList[indexPath.row] as! NSDictionary
            
            let isDivision = dicGroup[ApplicationData.dicKeyDivision]! as! Bool
            
            if !isDivision {
                hideDropDownTableView = true
                refreshListByGroup()
            }
        } else {
            if selectedTab == 1 {
                let viewController = Storyboards.AgeCategories.instantiateMatchInfoVC()
                
                if isDivisionRow || isElimination {
                    if let arrayOfDivision = teamFixuteuresDictionariesList[indexPath.section].values.first {
                        viewController.dicTeamFixture = arrayOfDivision[indexPath.row]
                    }
                } else {
                    viewController.dicTeamFixture = self.teamFixuteuresList[indexPath.row]
                }
                
                self.navigationController?.pushViewController(viewController, animated: true)
            }
        }
    }
}

extension AgeCategoriesGroupsSummaryVC {
    
    // TAB Matches
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
                
                if self.isDivisionRow {
                    
                    self.teamFixuteuresDivisionsList.removeAll()
                    self.teamFixuteuresDictionariesList.removeAll()
                    
                    // Fill section array
                    for i in 0..<self.teamFixuteuresList.count {
                        
                        let sectionName = self.teamFixuteuresList[i].competitionActualName
                        
                        if !self.teamFixuteuresDivisionsList.contains(sectionName) {
                            self.teamFixuteuresDivisionsList.append(sectionName)
                        }
                    }
                    
                    var sectionToScroll = -1
                    
                    // Create dictionary array
                    for i in 0..<self.teamFixuteuresDivisionsList.count {
                        
                        let section = self.teamFixuteuresDivisionsList[i]
                        
                        var newDictionary: [String: [TeamFixture]] = [:]
                        
                        let filteredArray = self.teamFixuteuresList.filter {
                            $0.competitionActualName == section
                        }
                        
                        newDictionary[section] = filteredArray
                        
                        self.teamFixuteuresDictionariesList.append(newDictionary)
                        
                        if let id = self.dicGroup.value(forKey: "id") as? Int {
                            for fixture in filteredArray {
                                if fixture.competitionId == id {
                                    sectionToScroll = i
                                }
                            }
                        }
                    }
                    
                    // Scroll to round section
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        self.table.beginUpdates()
                        self.table.endUpdates()
                        
                        if sectionToScroll != -1 {
                            self.table.scrollToRow(at: IndexPath(row: 0, section: sectionToScroll), at: .middle, animated: true)
                        }
                    }
                }
                
                if self.isElimination {
                    if self.teamFixuteuresList.count > 0  && self.teamFixuteuresList[0].isKnockoutPlacingMatches {
                        print("isElimination")
                        
                        self.teamFixuteuresDivisionsList.removeAll()
                        self.teamFixuteuresDictionariesList.removeAll()
                        
                        // Fill section array
                        for i in 0..<self.teamFixuteuresList.count {
                            
                            let sectionName = self.teamFixuteuresList[i].competationRoundNo
                            
                            if !self.teamFixuteuresDivisionsList.contains(sectionName) {
                                self.teamFixuteuresDivisionsList.append(sectionName)
                            }
                        }
                        
                        var sectionToScroll = -1
                        
                        // Create dictionary array
                        for i in 0..<self.teamFixuteuresDivisionsList.count {
                            
                            let section = self.teamFixuteuresDivisionsList[i]
                            
                            var newDictionary: [String: [TeamFixture]] = [:]
                            
                            let filteredArray = self.teamFixuteuresList.filter {
                                $0.competationRoundNo == section
                            }
                            
                            newDictionary[section] = filteredArray
                            
                            self.teamFixuteuresDictionariesList.append(newDictionary)
                            
                            if let id = self.dicGroup.value(forKey: "id") as? Int {
                                for fixture in filteredArray {
                                    if fixture.competitionId == id {
                                        sectionToScroll = i
                                    }
                                }
                            }
                        }
                        
                        // Scroll to round section
                        DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                            self.table.beginUpdates()
                            self.table.endUpdates()
                            
                            if sectionToScroll != -1 {
                                self.table.scrollToRow(at: IndexPath(row: 0, section: sectionToScroll), at: .middle, animated: true)
                            }
                        }
                        
                    } else {
                        self.isElimination = false
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
                    
                    if let error = result.value(forKey: "error") as? String {
                        if error == "token_expired"{
                            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)
                            UIApplication.shared.keyWindow?.rootViewController = UINavigationController(rootViewController: Storyboards.Main.instantiateLandingVC())
                        }
                    }
                }
            }
        })
    }
    
    // TAB Standings
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
    
    func refreshListByGroup() {
        
        if let title = dicGroup.value(forKey: "display_name") as? String {
            lblGroupName.text = title
        }
        
        isDivisionRow = dicGroup[ApplicationData.dicKeyDivisionRow] as! Bool
        print("isDivisionRow: \(isDivisionRow)")
        
        groupId = dicGroup.value(forKey: "id") as! Int
        
        if let type = dicGroup.value(forKey: "competation_type") as? String {
            if type == "Elimination" {
                tabStandingsSeparator.backgroundColor = .clear
                tabStandingView.backgroundColor = .gray
                tabStandingView.isUserInteractionEnabled = false
                tabMatchesSeparator.backgroundColor = .AppColor()
                
                self.view.layoutIfNeeded()
                
                // Selects tab 1
                selectedTab = 1
                
                if !isDivisionRow {
                    isElimination = true
                }
                
                sendGetFixturesRequest()
            } else {
                tabStandingView.backgroundColor = .white
                tabStandingView.isUserInteractionEnabled = true
                
                if selectedTab == 0 {
                    tabStandingsSeparator.backgroundColor = .AppColor()
                    tabMatchesSeparator.backgroundColor = .clear
                }
                
                isElimination = false
                sendGetFixturesRequest()
                sendGetGroupStadingsRequest()
            }
        }
    }
}
