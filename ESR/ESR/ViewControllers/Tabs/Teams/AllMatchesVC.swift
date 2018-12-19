//
//  AllMatchesVC.swift
//  ESR
//
//  Created by Pratik Patel on 19/12/18.
//

import UIKit

class AllMatchesVC: SuperViewController {
    
    @IBOutlet var table: UITableView!
    var dicTeam: NSDictionary!
    var teamFixuteuresList = [TeamFixture]()
    
    var heightGroupSummaryMatchesCell: CGFloat = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_team")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        if let name = dicTeam.value(forKey: "name") as? String {
           titleNavigationBar.lblTitle.text = name
        }
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.GroupSummaryMatchesCell)
        heightGroupSummaryMatchesCell = (cellOwner.cell as! GroupSummaryMatchesCell).getCellHeight()
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        sendGetFixturesRequest()
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
    
    func sendGetFixturesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournamentId"] = selectedTournament.id
        }
        
        parameters["club_id"] = dicTeam.value(forKey: "club_id") as! Int
        
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

extension AllMatchesVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension AllMatchesVC: UITableViewDelegate, UITableViewDataSource {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return teamFixuteuresList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightGroupSummaryMatchesCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
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
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        let viewController = Storyboards.AgeCategories.instantiateMatchInfoVC()
        viewController.dicTeamFixture = self.teamFixuteuresList[indexPath.row]
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
