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
    @IBOutlet var tabStandingView: UIView!
    @IBOutlet var tabMatchView: UIView!
    
    var groupSummaryStandingsHeader: UIView!
    var heightGroupSummaryStandingsCell: CGFloat = 0
    var heightGroupSummaryMatchesCell: CGFloat = 0
    var groupId: Int = NULL_ID
    
    var teamFixuteuresList = NSArray()
    var groupStandingsList = NSArray()
    
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
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryStandingsCell)
        heightGroupSummaryStandingsCell = (cellOwner.cell as! GroupSummaryStandingsCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryMatchesCell)
        heightGroupSummaryMatchesCell = (cellOwner.cell as! GroupSummaryMatchesCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: "GroupSummaryStandingsHeader")
        groupSummaryStandingsHeader = cellOwner.view
        
        
        sendGetGroupStadingsRequest()
    }
    
    override func viewDidLayoutSubviews() {
        super.viewDidLayoutSubviews()
        
        if groupSummaryStandingsHeader != nil {
            groupSummaryStandingsHeader.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: 130)
            self.table.tableHeaderView = groupSummaryStandingsHeader
        }
    }
    
    @objc func onTabStandingViewPressed(sender : UITapGestureRecognizer) {
        tabStandingsSeparator.backgroundColor = UIColor.AppColor()
        tabMatchesSeparator.backgroundColor = UIColor.clear
        selectedTab = 0
        sendGetGroupStadingsRequest()
    }
    
    @objc func onTabMatchViewPressed(sender : UITapGestureRecognizer) {
        tabMatchesSeparator.backgroundColor = UIColor.AppColor()
        tabStandingsSeparator.backgroundColor = UIColor.clear
        selectedTab = 1
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
            cell?.record = teamFixuteuresList[indexPath.row] as! NSDictionary
            cell?.reloadCell()
            return cell!
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if selectedTab == 0 {
            
        } else {
            
        }
        
        //        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsVC()
        //        viewController.ageCategoryId = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "id") as! Int
        //        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
