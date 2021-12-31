import { createStackNavigator } from "react-navigation-stack";
import { createAppContainer } from "react-navigation";
import Home from "../components/Home";
import SignIn from "../components/SignIn";


const screens = {
        SignInScreen: {
        screen: SignIn,
    },
        HomeScreen: {
        screen: Home,
    },

}
const homeStack = createStackNavigator(
    screens,
    {
        defaultNavigationOptions: {
            headerStyle: {
                backgroundColor: "#009387",
            },
            headerTintColor: '#fff',
            headerTitleStyle: {
                textAlign:'center',
                fontWeight: 'bold',
            },
        },
    },
    {initialRouteName: 'HomeScreen'}
    );

export default createAppContainer(homeStack);