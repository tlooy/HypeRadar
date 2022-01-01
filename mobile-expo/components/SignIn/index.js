import React, { Component } from 'react';
import { View, Pressable, Text, TextInput, TouchableOpacity, Button} from 'react-native';
import styles from './style';
import Feather from 'react-native-vector-icons/Feather';

export default class signin extends Component {
  constructor(props) {
    super(props);
    this.state = {
      email : '',
      password : '',
      check_textInputChange : false,
      secureTextEntry : true,
    };
  }

  CheckSigninCredentials=()=>{
    var Email = this.state.email;
    var Password = this.state.password;
    if ((Email.length==0) || (Password.length==0)){
      alert("Required Field Is Missing!!!");
    }else{
      // TODO change this to localhost or something else that will work when we promote this to prod
      var APIURL = "http://10.0.0.40/HypeRadar/mobile-expo/backend/signin.php";

      var headers = {
        'Accept' : 'application/json',
        'Content-Type' : 'application/json'
      };
            
      var Data ={
        'Email': Email,
        'Password': Password
      };

      fetch(APIURL,{
        method: 'POST',
        headers: headers,
        body: JSON.stringify(Data)
      })
      .then((Response)=>Response.json())
      .then((Response)=>{
        alert(Response[0].Message+"! Your User ID = "+Response[1].UserID)
        if (Response[0].Message == "Success") {
          console.log("true")
          console.log( "UserID = ", Response[1].UserID);
//          UserID = Response[1].UserID;
          this.props.navigation.navigate("HomeScreen", { UserID: Response[1].UserID });
        }
        console.log(Data);
      })
      .catch((error)=>{
        console.error("ERROR FOUND" + error);
      })
    }
    
    
  }

  updateSecureTextEntry(){
    this.setState({
      ...this.state,
      secureTextEntry: !this.state.secureTextEntry
    });
  }

  render() {
    return (
      <View style={styles.viewStyle}>
          <View style={styles.action}>
            <Text style={styles.txt}> Welcome to HypeRadar! </Text>
          </View>

          <View style={styles.action}>
            <TextInput
              placeholder="Enter Email or User Name"
              placeholderTextColor="#ff0000"
              style={styles.textInput}
              onChangeText={email=>this.setState({email})}
              />
          </View>

          <View style={styles.action}>
            <TextInput
              placeholder="Enter Password"
              placeholderTextColor="#ff0000"
              style={styles.textInput}
              secureTextEntry={this.state.secureTextEntry ? true : false}
              onChangeText={password=>this.setState({password})}
              />
                <TouchableOpacity
                  onPress={this.updateSecureTextEntry.bind(this)}>
                  {this.state.secureTextEntry ?
                  <Feather
                  name="eye-off"
                  color="grey"
                  size={20}
                  />
                :  
                  <Feather
                  name="eye"
                  color="black"
                  size={20}
                  />
                }
                </TouchableOpacity>  
          </View>


                {/* Button */}

                <View style={styles.loginButtonSection}>    
                  <Pressable
                    style={styles.loginButton} 
                    onPress={()=>{
                      this.CheckSigninCredentials()
                    }}
                    >
                      <Text style={styles.text}>Sign In</Text>
                  </Pressable>
                </View>
      </View>
    );
  }
}
