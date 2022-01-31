import { createStackNavigator } from "react-navigation-stack";
import { createAppContainer } from "react-navigation";
import Home from "../components/Home";
import SignIn from "../components/SignIn";
import Topics from "../components/Topics";

const screens = {
        SignInScreen: {
        screen: SignIn,
    },
        HomeScreen: {
        screen: Home,
    },
        TopicsScreen: {
        screen: Topics,
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
    {initialRouteName: 'SignInScreen'}
    );

export default createAppContainer(homeStack);
