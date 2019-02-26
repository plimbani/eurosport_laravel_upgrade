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
    
    @IBOutlet var sectionMatchView: UIView!
    @IBOutlet var sectionGroupView: UIView!
    @IBOutlet var lblSectionGroupName: UILabel!
    
    @IBOutlet var lblMatchViewNoLeagueData: UILabel!
    @IBOutlet var lblGroupViewNoLeagueData: UILabel!
    
    var isMatchLeagueDataEmpty = false
    var isGroupLeagueDataEmpty = false
    
    var teamFixuteuresList = [TeamFixture]()
    var groupStandingsList = [GroupStanding]()
    
    var heightGroupSummaryStandingsCell: CGFloat = 0
    var heightGroupSummaryMatchesCell: CGFloat = 0
    var heightTeamListCell: CGFloat = 0
    
    var dicTeam: NSDictionary!
    var dicTableData = NSMutableDictionary()
    
    var rotateToPortrait = false
    var viewGraphicImgURL = NULL_STRING
    
    let btnViewScheduleAttributes : [NSAttributedStringKey: Any] = [
        NSAttributedStringKey.font : UIFont.init(name: Font.HELVETICA_REGULAR, size: 15.0),
        NSAttributedStringKey.foregroundColor : UIColor.viewScheduleBlue,
        NSAttributedStringKey.underlineStyle : NSUnderlineStyle.styleSingle.rawValue]
    
    private enum SectionIndex: String {
        case group = "0"
        case match = "1"
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
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
                mainTabViewController.contentView.layoutIfNeeded()
                mainTabViewController.contentView.updateConstraints()
            }
        }
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_team")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        lblMatchViewNoLeagueData.text = String.localize(key: "string_no_league_data")
        lblGroupViewNoLeagueData.text = String.localize(key: "string_no_league_data")
        
        btnViewSchedule.setAttributedTitle(NSMutableAttributedString(string: "View schedule",
                                                                     attributes: btnViewScheduleAttributes), for: .normal)
        
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
        tableViewHeader.frame.size = CGSize(width:table.frame.width, height: 120)
        table.tableHeaderView = tableViewHeader
        setHeaderValues()
        
        dicTableData[SectionIndex.group.rawValue] = self.groupStandingsList
        dicTableData[SectionIndex.match.rawValue] = self.teamFixuteuresList
        
        sendGetFixturesRequest()
        sendGetGroupStadingsRequest()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @IBAction func btnViewSchedulePressed(_ sender: UIButton) {
        let viewController = Storyboards.Main.instantiateViewScheduleImageVC()
        viewController.imgURL = viewGraphicImgURL
        self.navigationController?.pushViewController(viewController, animated: true)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    func setHeaderValues() {
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
                    self.tableViewHeader.frame.size = CGSize(width: self.table.frame.width, height: 155)
                    self.table.tableHeaderView?.layoutIfNeeded()
                } else {
                    self.heightConstraintBtnViewSchedule.constant = 0
                }
            }
        }, failure: { result in })
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
        
        parameters["teamId"] = dicTeam.value(forKey: "id") as! Int
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
        
        parameters["competitionId"] = dicTeam.value(forKey: "GroupId") as! Int
        
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
}

extension TeamVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension TeamVC: UITableViewDelegate, UITableViewDataSource {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if section == Int(SectionIndex.group.rawValue) {
            if let array = dicTableData.value(forKey: "\(section)") as? [GroupStanding] {
                return array.count + 2
            }
        } else if section == Int(SectionIndex.match.rawValue) {
            if let array = dicTableData.value(forKey: "\(section)") as? [TeamFixture] {
                return array.count + 1
            }
        }
        return 0
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        if indexPath.section == Int(SectionIndex.group.rawValue) {
            
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
    }
    
    func numberOfSections(in tableView: UITableView) -> Int {
        return 2
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if indexPath.section == Int(SectionIndex.group.rawValue) {
            
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
                
                let dic = groupStandingsList[indexPath.row]
                cell?.record = dic
                
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
        if indexPath.section == Int(SectionIndex.group.rawValue) {
            
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
        if section == Int(SectionIndex.group.rawValue) {
            return sectionGroupView
        } else {
            return sectionMatchView
        }
    }
    
    func tableView(_ tableView: UITableView, heightForHeaderInSection section: Int) -> CGFloat {
        if section == Int(SectionIndex.group.rawValue) {
            return isGroupLeagueDataEmpty ? 100 : 50
        }
        
        return isMatchLeagueDataEmpty ? 100 : 50
    }
}
