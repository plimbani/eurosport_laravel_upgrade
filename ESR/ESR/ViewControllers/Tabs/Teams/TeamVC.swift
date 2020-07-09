//
//  TeamVC.swift
//  ESR
//
//  Created by Pratik Patel on 08/12/18.
//

import UIKit

class TeamVC: SuperViewController {

    @IBOutlet var table: UITableView!
    // Table view header
    @IBOutlet var tableViewHeader: UIView!
    @IBOutlet var tableViewHeaderInnerContiner: UIView!
    @IBOutlet var lblTeamName: UILabel!
    @IBOutlet var lblCountry: UILabel!
    @IBOutlet var imgCountry: UIImageView!
    @IBOutlet var lblGroup: UILabel!
    @IBOutlet var btnViewSchedule: UIButton!
    @IBOutlet var heightConstraintBtnViewSchedule: NSLayoutConstraint!
    
    @IBOutlet var stackviewMatchView: UIStackView!
    @IBOutlet var stackviewGroupView: UIStackView!
    @IBOutlet var noLeagueMatchView: UIView!
    @IBOutlet var noLeagueGroupView: UIView!
    @IBOutlet var sectionMatchView: UIView!
    @IBOutlet var sectionGroupView: UIView!
    @IBOutlet var lblSectionGroupName: UILabel!
    
    @IBOutlet var lblMatchViewNoLeagueData: UILabel!
    @IBOutlet var lblGroupViewNoLeagueData: UILabel!
    
    var isMatchLeagueDataEmpty = false {
        didSet {
            noLeagueMatchView.isHidden = isMatchLeagueDataEmpty
        }
    }
    
    var isGroupLeagueDataEmpty = false {
        didSet {
            noLeagueGroupView.isHidden = isGroupLeagueDataEmpty
        }
    }
    
    var teamFixuteuresList = [TeamFixture]()
    var groupStandingsList = [GroupStanding]()
    
    var heightGroupSummaryStandingsCell: CGFloat = 0
    var heightGroupSummaryMatchesCell: CGFloat = 0
    var heightTeamListCell: CGFloat = 0
    
    var dicTeam: NSDictionary!
    var dicTableData = NSMutableDictionary()
    
    var rotateToPortrait = false
    var viewGraphicImgURL = NULL_STRING
    
    var HEADER_HEIGHT: CGFloat = 160
    var HEADER_HEIGHT_AFTER_HIDE: CGFloat = 120
    
    @IBOutlet var tabStandingsSeparator: UIView!
    @IBOutlet var tabMatchesSeparator: UIView!
    @IBOutlet var tabStandingView: UIView!
    @IBOutlet var tabMatchView: UIView!
    @IBOutlet var teamSelectionView: UIView!
    @IBOutlet var lblSelectedTeamName: UILabel!
    
    var selectedPickerPosition = 0
    
    var titleList = [String]()
    var teamList = NSMutableArray()
    var teamTitleList = [String]()
    
    var selectedTab = 0 {
        didSet {
            if selectedTab == 0 {
                tabStandingsSeparator.backgroundColor = UIColor.AppColor()
                tabMatchesSeparator.backgroundColor = UIColor.clear
                
                if groupStandingsList.count == 0 {
                    getGroupStadingsAPI()
                } else {
                    table.reloadData()
                }
            } else {
                tabMatchesSeparator.backgroundColor = UIColor.AppColor()
                tabStandingsSeparator.backgroundColor = UIColor.clear
                
                if teamFixuteuresList.count == 0 {
                    getFixturesRequestAPI()
                } else {
                    table.reloadData()
                }
            }
        }
    }
    
    let btnViewScheduleAttributes : [NSAttributedStringKey: Any] = [
        NSAttributedStringKey.font : UIFont.init(name: Font.HELVETICA_REGULAR, size: 18.0)!,
        NSAttributedStringKey.foregroundColor : UIColor.viewScheduleBlue,
        NSAttributedStringKey.underlineStyle : NSUnderlineStyle.styleSingle.rawValue]
    
    private enum SectionIndex: String {
        case group = "0"
        case match = "1"
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
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
    
    func initialize() {
        let adjustForTabbarInsets: UIEdgeInsets = UIEdgeInsetsMake(0, 0, 60, 0)
        table.contentInset = adjustForTabbarInsets
        table.scrollIndicatorInsets = adjustForTabbarInsets
        
        titleNavigationBar.lblTitle.text = String.localize(key: "title_team")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        isMatchLeagueDataEmpty = true
        isGroupLeagueDataEmpty = true
        
        lblMatchViewNoLeagueData.text = String.localize(key: "string_no_league_data")
        lblGroupViewNoLeagueData.text = String.localize(key: "string_no_league_data")
        
        btnViewSchedule.setAttributedTitle(NSMutableAttributedString(string: "View schedule",
                                                                     attributes: btnViewScheduleAttributes), for: .normal)
        
        self.tabStandingView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabStandingViewPressed)))
        self.tabMatchView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabMatchViewPressed)))
        self.teamSelectionView.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTeamViewPressed)))
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryStandingsCell)
        heightGroupSummaryStandingsCell = (cellOwner.cell as! GroupSummaryStandingsCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryMatchesCell)
        heightGroupSummaryMatchesCell = (cellOwner.cell as! GroupSummaryMatchesCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TeamListCell)
        heightTeamListCell = (cellOwner.cell as! TeamListCell).getCellHeight()
        
        ApplicationData.setBorder(view: tableViewHeaderInnerContiner, Color: .btnDisable, CornerRadius: 0.0, Thickness: 1.0)
        tableViewHeader.frame.size = CGSize(width:table.frame.width, height: HEADER_HEIGHT_AFTER_HIDE)
        table.tableHeaderView = tableViewHeader
        
        setHeaderValues(dicTeam: dicTeam)
        
        dicTableData[SectionIndex.group.rawValue] = self.groupStandingsList
        dicTableData[SectionIndex.match.rawValue] = self.teamFixuteuresList
        
        tabStandingsSeparator.backgroundColor = .AppColor()
        tabMatchesSeparator.backgroundColor = .clear

        getFixturesRequestAPI()
        getGroupStadingsAPI()
        
        teamTitleList = teamList.map({ ($0 as! NSDictionary).value(forKey: "name") as! String })
        
        if teamTitleList.count > 0 {
            lblSelectedTeamName.text = teamTitleList[selectedPickerPosition]
        } else {
            getTeamListByAgeCategoryID()
        }
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func onTabStandingViewPressed(sender : UITapGestureRecognizer) {
        selectedTab = 0
    }
    
    @objc func onTabMatchViewPressed(sender : UITapGestureRecognizer) {
        selectedTab = 1
    }
    
    @objc func onTeamViewPressed(sender : UITapGestureRecognizer) {
        showPickerVC(selectedPosition: selectedPickerPosition, titleList: teamTitleList, delegate: self)
    }
    
    @IBAction func btnViewSchedulePressed(_ sender: UIButton) {
        let viewController = Storyboards.Main.instantiateViewScheduleImageVC()
        viewController.isFromTeamVC = true
        viewController.base64String = viewGraphicImgURL
        self.navigationController?.pushViewController(viewController, animated: true)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    func setHeaderValues(dicTeam: NSDictionary) {
        var name = NULL_STRING
        var groupName = NULL_STRING
        
        if let text = dicTeam.value(forKey: "name") as? String {
            name = text + " "
        }
        
        if let text = dicTeam.value(forKey: "CategoryAge") as? String {
            name += "(\(text))"
        }
        
        if let text = dicTeam.value(forKey: "countryLogo") as? String {
            imgCountry.sd_setImage(with: URL(string: text), placeholderImage:nil)
        }
        
        if let text = dicTeam.value(forKey: "CountryName") as? String {
            lblCountry.text = text
        }
        
        if let text = dicTeam.value(forKey: "ageGroupName") as? String {
            groupName = text
        }
        
        if let text = dicTeam.value(forKey: "group_name") as? String {
            if !text.isEmpty {
                groupName += ", " + text
                lblGroup.text = groupName
                lblSectionGroupName.text = text + " " + String.localize(key: "string_league_table")
            }
        }
        
        if let text = dicTeam.value(forKey: "age_group_id") as? Int {
            print("age_group_id \(text)")
            sendGetViewGraphicImageRequest(ageGroupId: text)
        }
        
        lblTeamName.text = name
    }
    
    func sendGetViewGraphicImageRequest(ageGroupId: Int) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["age_category"] = "\(ageGroupId)"
        
        ApiManager().getViewGraphicImage(parameters, success: { result in
            DispatchQueue.main.async {
                if let imgURL = result as? String {
                    self.heightConstraintBtnViewSchedule.constant = 30
                    self.viewGraphicImgURL = imgURL
                    self.tableViewHeader.frame.size = CGSize(width: self.table.frame.width, height: self.HEADER_HEIGHT)
                    
                    self.table.beginUpdates()
                    self.table.endUpdates()
                } else {
                    self.heightConstraintBtnViewSchedule.constant = 0
                }
            }
        }, failure: { result in })
    }
    
    func getFixturesRequestAPI(teamId: Int = -1) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }
        
        if teamId != -1 {
            parameters["teamId"] = teamId
        } else {
            parameters["teamId"] = dicTeam.value(forKey: "id") as! Int
        }
        
        parameters["is_scheduled"] = "1"
        
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
                    
                    // Sort array by start date
                    self.teamFixuteuresList.sort(by: { (t1, t2) -> Bool in
                        
                        if let date1 = t1.matchDatetimeObj {
                            if let date2 = t1.matchDatetimeObj {
                                return (date1.timeIntervalSinceNow < date2.timeIntervalSinceNow)
                            } else {
                                return true
                            }
                        } else {
                            return true
                        }
                    })
                    
                    self.dicTableData[SectionIndex.match.rawValue] = self.teamFixuteuresList
                }
                
                self.isMatchLeagueDataEmpty = (self.teamFixuteuresList.count == 0)
                self.lblMatchViewNoLeagueData.isHidden = !(self.teamFixuteuresList.count == 0)
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

                            if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                                keyWindow.rootViewController = Storyboards.Main.instantiateLandingVC()
                            }
                        }
                    }
                }
            }
        })
    }
    
    func getGroupStadingsAPI(groupId: Int = -1) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }
        
        if groupId != -1 {
            parameters["competitionId"] = groupId
        } else {
            parameters["competitionId"] = dicTeam.value(forKey: "GroupId") as! Int
        }
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        ApiManager().getGroupStandings(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                self.groupStandingsList.removeAll()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    
                    for i in 0..<data.count {
                        self.groupStandingsList.append(ParseManager.parseGroupStandings(data[i] as! NSDictionary))
                    }
                }
                
                self.dicTableData[SectionIndex.group.rawValue] = self.groupStandingsList
                self.isGroupLeagueDataEmpty = (self.groupStandingsList.count == 0)
                self.lblGroupViewNoLeagueData.isHidden = !(self.groupStandingsList.count == 0)
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func getTournamentTeamDetailsAPI(teamId: Int) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        parameters["team_id"] = teamId
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        ApiManager().getTournamentTeamDetails(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                if let array = result.value(forKey: "data") as? NSArray {
                    if array.count > 0 {
                        self.setHeaderValues(dicTeam: array[0] as! NSDictionary)
                    }
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func getTeamListByAgeCategoryID() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        parameters["age_group_id"] = dicTeam.value(forKey: "ageGroupId") as! Int
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getTeamList(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.teamList = NSMutableArray.init(array: data)
                }
                
                let descriptor: NSSortDescriptor = NSSortDescriptor(key: "name", ascending: true)
                self.teamList = NSMutableArray.init(array: self.teamList.sortedArray(using: [descriptor]))
                self.teamTitleList = self.teamList.map({ ($0 as! NSDictionary).value(forKey: "name") as! String })
                
                for i in 0..<self.teamList.count{
                    if (self.dicTeam.value(forKey: "id") as! Int) == (self.teamList[i] as! NSDictionary).value(forKey: "id") as! Int {
                        self.selectedPickerPosition = i
                        break
                    }
                }
                
                if self.teamTitleList.count > 0 {
                    self.lblSelectedTeamName.text = self.teamTitleList[self.selectedPickerPosition]
                }
            }
        }) { (result) in
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
        }
    }
}

extension TeamVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension TeamVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension TeamVC: GroupSummaryStandingsCellDelegate {
    func groupSummaryStandingsCellBtnTeamNamePressed(indexPath: IndexPath) {
        
        let selectedTeam = groupStandingsList[indexPath.row]
        
        var change = false
        
        for i in 0..<teamList.count{
            if let id = (teamList[i] as! NSDictionary).value(forKey: "id") as? Int {
                if id == selectedTeam.teamId && id != (dicTeam.value(forKey: "id") as! Int) {
                    dicTeam = teamList[i] as! NSDictionary
                    selectedPickerPosition = i
                    change = true
                    lblSelectedTeamName.text = (teamList[i] as! NSDictionary).value(forKey: "name") as! String
                    break
                }
            }
        }
        
        if change {
            getFixturesRequestAPI(teamId: dicTeam.value(forKey: "id") as! Int)
            getGroupStadingsAPI(groupId: dicTeam.value(forKey: "GroupId") as! Int)
            getTournamentTeamDetailsAPI(teamId: dicTeam.value(forKey: "id") as! Int)
        }
    }
}

extension TeamVC: PickerVCDelegate {
    func pickerVCDoneBtnPressed(title: String, lastPosition: Int) {
        selectedPickerPosition = lastPosition
        lblSelectedTeamName.text = title
        
        let selectedTeam = teamList[selectedPickerPosition] as! NSDictionary
        dicTeam = selectedTeam
        
        getFixturesRequestAPI(teamId: selectedTeam.value(forKey: "id") as! Int)
        getGroupStadingsAPI(groupId: selectedTeam.value(forKey: "GroupId") as! Int)
        getTournamentTeamDetailsAPI(teamId: selectedTeam.value(forKey: "id") as! Int)
    }
    
    func pickerVCCancelBtnPressed() {}
}

extension TeamVC: UITableViewDelegate, UITableViewDataSource {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        if selectedTab == 0 {
            return groupStandingsList.count + 2
        } else {
            return teamFixuteuresList.count + 1
        }
        
        /*if section == Int(SectionIndex.group.rawValue) {
            if let array = dicTableData.value(forKey: "\(section)") as? [GroupStanding] {
                return array.count + 2
            }
        } else if section == Int(SectionIndex.match.rawValue) {
            if let array = dicTableData.value(forKey: "\(section)") as? [TeamFixture] {
                return array.count + 1
            }
        }*/
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        
        if selectedTab == 0 {
            if indexPath.row == groupStandingsList.count + 1 || indexPath.row  == groupStandingsList.count {
                return heightTeamListCell
            }
            
            return heightGroupSummaryStandingsCell
        } else {
            if indexPath.row == teamFixuteuresList.count {
                return heightTeamListCell
            }
            
            return heightGroupSummaryMatchesCell
        }
        
        /*if indexPath.section == Int(SectionIndex.group.rawValue) {
            
            if indexPath.row == groupStandingsList.count + 1 || indexPath.row  == groupStandingsList.count {
                return heightTeamListCell
            }
            
            return heightGroupSummaryStandingsCell
        } else {
            if indexPath.row == teamFixuteuresList.count {
                return heightTeamListCell
            }
            
            return heightGroupSummaryMatchesCell
        }*/
    }
    
    func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if selectedTab == 0 {
            
            if indexPath.row == groupStandingsList.count || indexPath.row == groupStandingsList.count + 1 {
                var cell = tableView.dequeueReusableCell(withIdentifier: "TeamListCell") as? TeamListCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "TeamListCell")
                    cell = cellOwner.cell as? TeamListCell
                }
                
                if indexPath.row == groupStandingsList.count + 1 {
                    cell?.lblTitle.text = "Match schedule"
                } else {
                    cell?.lblTitle.text = "Group details"
                }
                
                return cell!
            } else {
                var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryStandingsCell") as? GroupSummaryStandingsCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryStandingsCell")
                    cell = cellOwner.cell as? GroupSummaryStandingsCell
                }
                
                cell?.indexPath = indexPath
                cell?.delegate = self
                let dic = groupStandingsList[indexPath.row]
                cell?.record = dic
                
                cell?.backgroundColor = UIColor.white
                
                if let id = dicTeam.value(forKey: "id") as? Int {
                    if dic.teamId == id {
                        cell?.backgroundColor = UIColor.init(hexString: "#c5dba7")
                    }
                }
                
                cell?.reloadCell()
                return cell!
            }
        } else {
            if indexPath.row == teamFixuteuresList.count {
                var cell = tableView.dequeueReusableCell(withIdentifier: "TeamListCell") as? TeamListCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "TeamListCell")
                    cell = cellOwner.cell as? TeamListCell
                }
                cell?.lblTitle.text = "View all club matches"
                return cell!
            } else {
                var cell = tableView.dequeueReusableCell(withIdentifier: "GroupSummaryMatchesCell") as? GroupSummaryMatchesCell
                if cell == nil {
                    _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryMatchesCell")
                    cell = cellOwner.cell as? GroupSummaryMatchesCell
                }
                
                let dic = teamFixuteuresList[indexPath.row]
                cell?.record = dic
                cell?.reloadCell()
                return cell!
            }
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        if selectedTab == 0 {
            
            if indexPath.row == groupStandingsList.count {
                // Group details click
                if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                    mainTabViewController.hideTabbar()
                }
                
                let viewController = Storyboards.AgeCategories.instantiateGroupDetailsVC()
                viewController.groupStandingsList = self.groupStandingsList
                viewController.groupName = lblSectionGroupName.text!
                self.navigationController?.pushViewController(viewController, animated: true)
            } else if indexPath.row == groupStandingsList.count + 1 {
                // Match schedule click
                let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsVC()
                
                if let ageGroupId = dicTeam.value(forKey: "age_group_id") as? Int {
                    viewController.ageCategoryId = ageGroupId
                }
                
                self.navigationController?.pushViewController(viewController, animated: true)
            }
        } else {
            
            if indexPath.row == teamFixuteuresList.count {
                // view all club match click
                let viewController = Storyboards.Teams.instantiateAllMatchesVC()
                viewController.dicTeam = dicTeam
                self.navigationController?.pushViewController(viewController, animated: true)
            } else {
                // Cell click
                let viewController = Storyboards.AgeCategories.instantiateMatchInfoVC()
                viewController.dicTeamFixture = self.teamFixuteuresList[indexPath.row]
                self.navigationController?.pushViewController(viewController, animated: true)
            }
        }
    }
    
    func tableView(_ tableView: UITableView, viewForHeaderInSection section: Int) -> UIView? {
        if selectedTab == 0 {
            return sectionGroupView
        } else {
            return sectionMatchView
        }
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        if selectedTab == 0 {
            return isGroupLeagueDataEmpty ? 100 : 50
        }
        
        return isMatchLeagueDataEmpty ? 100 : 50
    }
}
