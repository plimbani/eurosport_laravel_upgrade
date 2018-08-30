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
    
    var heightGroupSummaryStandingsCell: CGFloat = 0
    var heightGroupSummaryMatchesCell: CGFloat = 0
    var groupId: Int = NULL_ID
    var groupName = NULL_STRING
    
    var teamFixuteuresList = [TeamFixture]()
    var groupStandingsList = NSArray()
    
    var pickerHandlerView: PickerHandlerView!
    var titleList = [String]()
    
    var selectedTab = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_age_categories_groups")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        var gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onTabStandingViewPressed))
        self.tabStandingView.addGestureRecognizer(gesture)
        
        gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onTabMatchViewPressed))
        self.tabMatchView.addGestureRecognizer(gesture)
        
        gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onGroupViewPressed))
        self.groupSelectionView.addGestureRecognizer(gesture)
        
        gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onFooterGroupStandingView))
        self.footerGroupStandingView.addGestureRecognizer(gesture)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryStandingsCell)
        heightGroupSummaryStandingsCell = (cellOwner.cell as! GroupSummaryStandingsCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryMatchesCell)
        heightGroupSummaryMatchesCell = (cellOwner.cell as! GroupSummaryMatchesCell).getCellHeight()
        
        for group in ApplicationData.groupsList {
            titleList.append((group as! NSDictionary).value(forKey: "name") as! String)
        }
        
        pickerHandlerView = getPickerView(titleList)
        pickerHandlerView.delegate = self
        self.view.addSubview(pickerHandlerView)
        
        lblGroupName.text = groupName
        
        if headerGroupStandingView != nil {
            headerGroupStandingView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 60)
        }
        
        if headerGroupMatchesView != nil {
            headerGroupMatchesView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 40)
        }
        
        if footerGroupStandingView != nil {
            footerGroupStandingView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 50)
        }
        
        sendGetGroupStadingsRequest()
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
        pickerHandlerView.show()
    }
    
    @objc func onFooterGroupStandingView(sender : UITapGestureRecognizer) {
        
    }
    
    func sendGetFixturesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["tournamentId"] = userData.tournamentId
        }
        
        parameters["competitionId"] = groupId
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        ApiManager().getMatchFixtures(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    
                    for i in 0..<data.count {
                        self.teamFixuteuresList.append(ParseManager.parseTeamFixture(data[i] as! NSDictionary))
                    }
                    
                    // Sort array by start date
                    self.teamFixuteuresList.sort(by: { (t1, t2) -> Bool in
                        return (t1.matchDatetimeObj.timeIntervalSinceNow > t2.matchDatetimeObj.timeIntervalSinceNow)
                    })
                }
                
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
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["tournamentId"] = userData.tournamentId
        }
        
        parameters["competitionId"] = groupId
        
        var serverTournamentData: [String: Any] = [:]
        serverTournamentData["tournamentData"] = parameters
        
        ApiManager().getGroupStandings(serverTournamentData, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.groupStandingsList = data
                }
                
                self.table.reloadData()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
}

extension AgeCategoriesGroupsSummaryVC: PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {
        lblGroupName.text = title
        
        let groupDic = ApplicationData.groupsList[pickerHandlerView.selectedPickerPosition] as! NSDictionary
        
        if let type = groupDic.value(forKey: "competation_type") as? String {
            if type == "Elimination" {
                tabStandingsSeparator.backgroundColor = UIColor.clear
                tabStandingView.backgroundColor = UIColor.gray
                tabStandingView.isUserInteractionEnabled = false
                tabMatchesSeparator.backgroundColor = UIColor.AppColor()
                
                selectedTab = 1
                sendGetFixturesRequest()
            } else {
                tabStandingView.backgroundColor = UIColor.white
                tabStandingView.isUserInteractionEnabled = true
                
                sendGetFixturesRequest()
                sendGetGroupStadingsRequest()
            }
        }
    }
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
            cell?.record = groupStandingsList[indexPath.row] as! NSDictionary
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
        
        if selectedTab == 0 {
            
        } else {
            
        }
        
        //   let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsVC()
        //   viewController.ageCategoryId = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "id") as! Int
        //   self.navigationController?.pushViewController(viewController, animated: true)
    }
}
